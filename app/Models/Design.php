<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'name', 'design_code', 'emb_file', 'file_name', 'stitches',
        'height', 'width', 'area', 'category_id', 'needle_color',
        'design_format', 'design_img', 'design_price', 'description',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($design) {
            if (empty($design->design_code)) {
                $design->design_code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode()
    {
        $prefix = now()->format('my'); // MMYY e.g. "0626" for June 2026

        $latestCode = self::where('design_code', 'like', $prefix . '%')
            ->orderBy('design_code', 'desc')
            ->value('design_code');

        if (!$latestCode) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latestCode, 4) + 1;
        $nextCode = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        while (self::where('design_code', $nextCode)->exists()) {
            $sequence++;
            $nextCode = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }

        return $nextCode;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
