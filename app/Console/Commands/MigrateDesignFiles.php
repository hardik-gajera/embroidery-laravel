<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Design;

class MigrateDesignFiles extends Command
{
    protected $signature = 'migrate:design-files {--batch=20 : Records per query}';
    protected $description = 'Download missing design files from Odoo sequentially';

    public function handle()
    {
        $batchSize = (int) $this->option('batch');

        // Get set of Odoo IDs we already have images for (from file path like designs/images/123.png)
        $existingIds = [];
        $designs = Design::whereNotNull('design_img')->where('design_img', '!=', '')->pluck('design_img');
        foreach ($designs as $path) {
            if (preg_match('/designs\/images\/(\d+)\.png/', $path, $m)) {
                $existingIds[(int)$m[1]] = true;
            }
        }

        $odoo = DB::connection('odoo');
        $total = $odoo->table('embroidery_details')->count();
        $this->info("Total Odoo records: {$total} | Already have images for: " . count($existingIds));

        $missing = $total - count($existingIds);
        $this->info("Need to download: ~{$missing}");

        $bar = $this->output->createProgressBar($total);
        $downloaded = 0;
        $skipped = 0;
        $lastId = 0;

        while (true) {
            // Fetch only IDs first (fast - no binary)
            $ids = $odoo->table('embroidery_details')
                ->select(['id'])
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->limit($batchSize * 5)
                ->pluck('id')
                ->toArray();

            if (empty($ids)) break;

            // Filter out ones we already have
            $needIds = [];
            foreach ($ids as $id) {
                if (!isset($existingIds[$id])) {
                    $needIds[] = $id;
                } else {
                    $skipped++;
                    $bar->advance();
                }
            }
            $lastId = end($ids);

            if (empty($needIds)) continue;

            // Fetch binary data only for missing ones in small batches
            $needBatches = array_chunk($needIds, $batchSize);
            foreach ($needBatches as $batchIds) {
                try {
                    $records = $odoo->table('embroidery_details')
                        ->select(['id', 'design_code', 'file_name', 'emb_file', 'design_img'])
                        ->whereIn('id', $batchIds)
                        ->get();
                } catch (\Exception $e) {
                    $this->error(" Error: " . substr($e->getMessage(), 0, 100));
                    $bar->advance(count($batchIds));
                    continue;
                }

                foreach ($records as $d) {
                    $code = $d->design_code ?: 'ODO-' . $d->id;
                    $embPath = '';
                    $imgPath = null;

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

                    if ($embPath || $imgPath) {
                        Design::where('design_code', $code)->update(array_filter([
                            'emb_file' => $embPath ?: null,
                            'design_img' => $imgPath,
                        ]));
                        $downloaded++;
                    }

                    $bar->advance();
                }
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! Downloaded: {$downloaded} | Skipped: {$skipped}");
    }
}
