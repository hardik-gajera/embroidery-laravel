<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Design;
use App\Models\DesignPackage;
use App\Models\Customer;
use App\Models\CustomerPackagePurchase;

class MigrateFromOdoo extends Command
{
    protected $signature = 'migrate:odoo {--only= : Migrate specific (categories,designs,packages,customers,package-purchases)} {--with-files : Also download binary files for designs} {--resume : Resume only missing images} {--fix-categories : Only fix null categories without downloading files}';
    protected $description = 'Migrate data from Odoo PostgreSQL to Laravel';

    private $odoo;

    public function handle()
    {
        $this->odoo = DB::connection('odoo');
        $only = $this->option('only');

        if ($this->option('fix-categories')) {
            $this->fixCategories();
            return;
        }

        if (!$only || $only === 'categories') $this->migrateCategories();
        if (!$only || $only === 'designs') $this->migrateDesigns();
        if (!$only || $only === 'packages') $this->migratePackages();
        if (!$only || $only === 'customers') $this->migrateCustomers();
        if (!$only || $only === 'package-purchases') $this->migratePackagePurchases();

        $this->info('Migration completed!');
    }

    private function fixCategories()
    {
        $this->info('Fixing null categories from Odoo...');
        $categoryMap = cache('odoo_category_map', []);
        $parentCategoryMap = cache('odoo_parent_category_map', []);

        if (empty($categoryMap) && empty($parentCategoryMap)) {
            $this->info('  → Category map cache is empty. Running category migration first...');
            $this->migrateCategories();
            $categoryMap = cache('odoo_category_map', []);
            $parentCategoryMap = cache('odoo_parent_category_map', []);
        }

        $total = $this->odoo->table('embroidery_details')->count();
        $bar = $this->output->createProgressBar($total);
        $fixed = 0;

        $this->odoo->table('embroidery_details')
            ->select(['id', 'design_code', 'design_type'])
            ->orderBy('id')
            ->chunk(1000, function ($designs) use ($categoryMap, $parentCategoryMap, $bar, &$fixed) {
                foreach ($designs as $d) {
                    $code = $d->design_code ?: 'ODO-' . $d->id;
                    $categoryId = $categoryMap[$d->design_type] ?? $parentCategoryMap[$d->design_type] ?? null;

                    if ($categoryId) {
                        $updated = Design::where('design_code', $code)
                            ->whereNull('category_id')
                            ->update(['category_id' => $categoryId]);
                        if ($updated) $fixed++;
                    }
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine();
        $this->info("  → Fixed categories for {$fixed} designs.");
    }

    private function migrateCategories()
    {
        $this->info('Migrating parent categories...');
        $parents = $this->odoo->table('parent_design_type')->get();
        $parentMap = [];

        foreach ($parents as $parent) {
            $cat = Category::updateOrCreate(
                ['name' => $parent->name, 'parent_id' => null],
                ['created_at' => $parent->create_date, 'updated_at' => $parent->write_date]
            );
            $parentMap[$parent->id] = $cat->id;
        }
        $this->info("  → {$parents->count()} parent categories migrated.");

        $this->info('Migrating child categories...');
        $children = $this->odoo->table('design_type')->get();
        $childMap = [];

        foreach ($children as $child) {
            $parentId = $parentMap[$child->parent_design_type] ?? null;
            $cat = Category::updateOrCreate(
                ['name' => $child->name, 'parent_id' => $parentId],
                ['created_at' => $child->create_date, 'updated_at' => $child->write_date]
            );

            if ($child->image) {
                $imgData = is_resource($child->image) ? stream_get_contents($child->image) : $child->image;
                if ($imgData) {
                    $decoded = base64_decode($imgData, true);
                    $imgData = $decoded !== false ? $decoded : $imgData;
                    $filename = 'categories/' . $cat->id . '.jpg';
                    Storage::disk('public')->put($filename, $imgData);
                    $cat->update(['image' => $filename]);
                }
            }

            $childMap[$child->id] = $cat->id;
        }
        $this->info("  → {$children->count()} child categories migrated.");
        cache(['odoo_category_map' => $childMap], now()->addHours(24));
        cache(['odoo_parent_category_map' => $parentMap], now()->addHours(24));
    }

    private function migrateDesigns()
    {
        $withFiles = $this->option('with-files');
        $resume = $this->option('resume');
        $this->info('Migrating designs' . ($withFiles ? ' WITH files...' : ' (metadata only)...') . ($resume ? ' (RESUMING - only missing)' : ''));

        $categoryMap = cache('odoo_category_map', []);
        $parentCategoryMap = cache('odoo_parent_category_map', []);
        $batchSize = $withFiles ? 50 : 500;

        $columns = ['id', 'name', 'file_name', 'stiches', 'height', 'width', 'area',
                    'design_type', 'needle_color', 'design_format', 'design_price',
                    'description', 'design_code', 'create_date', 'write_date'];

        if ($withFiles) {
            $columns[] = 'emb_file';
            $columns[] = 'design_img';
        }

        $completeCodes = [];
        if ($resume) {
            $completeCodes = Design::whereNotNull('design_img')
                ->where('design_img', '!=', '')
                ->pluck('design_code')
                ->flip()
                ->toArray();
            $this->info("  → Will skip file download for " . count($completeCodes) . " designs that already have images.");
        }

        $total = $this->odoo->table('embroidery_details')->count();
        $bar = $this->output->createProgressBar($total);
        $migrated = 0;
        $skipped = 0;

        $this->odoo->table('embroidery_details')
            ->select($columns)
            ->orderBy('id')
            ->chunk($batchSize, function ($designs) use ($categoryMap, $parentCategoryMap, $bar, $withFiles, $resume, &$completeCodes, &$migrated, &$skipped) {
                foreach ($designs as $d) {
                    $code = $d->design_code ?: 'ODO-' . $d->id;
                    $alreadyHasImage = isset($completeCodes[$code]);

                    if ($resume && $alreadyHasImage) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }

                    $categoryId = $categoryMap[$d->design_type] ?? $parentCategoryMap[$d->design_type] ?? null;
                    $embPath = '';
                    $imgPath = null;

                    if ($withFiles) {
                        if ($d->emb_file) {
                            $fileData = is_resource($d->emb_file) ? stream_get_contents($d->emb_file) : $d->emb_file;
                            if ($fileData) {
                                $decoded = base64_decode($fileData, true);
                                $fileData = $decoded !== false ? $decoded : $fileData;
                                $ext = pathinfo($d->file_name ?? 'design.emb', PATHINFO_EXTENSION) ?: 'emb';
                                $embPath = 'designs/files/' . $d->id . '.' . $ext;
                                Storage::disk('public')->put($embPath, $fileData);
                            }
                        }
                        if ($d->design_img) {
                            $imgData = is_resource($d->design_img) ? stream_get_contents($d->design_img) : $d->design_img;
                            if ($imgData) {
                                $decoded = base64_decode($imgData, true);
                                $imgData = $decoded !== false ? $decoded : $imgData;
                                $imgPath = 'designs/images/' . $d->id . '.png';
                                Storage::disk('public')->put($imgPath, $imgData);
                            }
                        }
                    }

                    Design::updateOrCreate(
                        ['design_code' => $code],
                        [
                            'name' => $d->name,
                            'emb_file' => $embPath,
                            'file_name' => $d->file_name,
                            'stitches' => $d->stiches ?? 0,
                            'height' => $d->height,
                            'width' => $d->width,
                            'area' => $d->area,
                            'category_id' => $categoryId,
                            'needle_color' => $d->needle_color,
                            'design_format' => $d->design_format ?? 'emb',
                            'design_img' => $imgPath,
                            'design_price' => $d->design_price ?? 225,
                            'description' => $d->description,
                            'created_at' => $d->create_date,
                            'updated_at' => $d->write_date,
                        ]
                    );

                    $migrated++;
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine();
        $this->info("  → Done! Migrated: {$migrated}, Skipped: {$skipped}");
    }

    private function migratePackages()
    {
        $this->info('Migrating packages...');
        $packages = $this->odoo->table('design_package')->get();

        foreach ($packages as $p) {
            $pkg = DesignPackage::updateOrCreate(
                ['name' => $p->name],
                [
                    'number_of_design' => $p->number_of_design,
                    'time_period' => $p->time_period,
                    'price' => $p->price,
                    'state' => $p->state ?? 'draft',
                    'created_at' => $p->create_date,
                    'updated_at' => $p->write_date,
                ]
            );

            if ($p->package_img) {
                $imgData = is_resource($p->package_img) ? stream_get_contents($p->package_img) : $p->package_img;
                if ($imgData) {
                    $decoded = base64_decode($imgData, true);
                    $imgData = $decoded !== false ? $decoded : $imgData;
                    $filename = 'packages/' . $pkg->id . '.jpg';
                    Storage::disk('public')->put($filename, $imgData);
                    $pkg->update(['package_img' => $filename]);
                }
            }
        }
        $this->info("  → {$packages->count()} packages migrated.");
    }

    private function migrateCustomers()
    {
        $this->info('Migrating customers...');
        $customers = $this->odoo->table('res_partner')
            ->where('customer', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        $count = 0;
        foreach ($customers as $c) {
            Customer::updateOrCreate(
                ['email' => $c->email],
                [
                    'name' => $c->name ?? 'Unknown',
                    'mobile_no' => $c->mobile ?? $c->phone ?? '',
                    'downloaded_design' => 0,
                    'total_design' => 0,
                    'password' => 'password123',
                    'created_at' => $c->create_date,
                    'updated_at' => $c->write_date,
                ]
            );
            $count++;
        }
        $this->info("  → {$count} customers migrated.");
    }

    private function migratePackagePurchases()
    {
        $this->info('Migrating package purchases...');

        // Build maps: Odoo partner_id -> Laravel customer_id, Odoo package_id -> Laravel package_id
        $customerMap = [];
        $partners = $this->odoo->table('res_partner')
            ->where('customer', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get(['id', 'email']);

        foreach ($partners as $p) {
            $customer = Customer::where('email', $p->email)->first();
            if ($customer) {
                $customerMap[$p->id] = $customer->id;
            }
        }

        $packageMap = [];
        $odooPackages = $this->odoo->table('design_package')->get(['id', 'name']);
        foreach ($odooPackages as $op) {
            $pkg = DesignPackage::where('name', $op->name)->first();
            if ($pkg) {
                $packageMap[$op->id] = $pkg->id;
            }
        }

        // Migrate purchases
        $purchases = $this->odoo->table('design_package_purchase')->get();
        $count = 0;

        foreach ($purchases as $p) {
            $customerId = $customerMap[$p->partner_id] ?? null;
            $packageId = $packageMap[$p->package_id] ?? null;

            if (!$customerId || !$packageId) continue;

            CustomerPackagePurchase::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'package_id' => $packageId,
                    'start_date' => $p->start_date,
                ],
                [
                    'total' => $p->total ?? 0,
                    'downloaded' => $p->downloaded ?? 0,
                    'end_date' => $p->end_date,
                    'created_at' => $p->create_date ?? now(),
                    'updated_at' => $p->write_date ?? now(),
                ]
            );
            $count++;
        }

        $this->info("  → {$count} package purchases migrated.");
    }
}
