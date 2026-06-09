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
        // Get the latest design code
        $latestCode = self::whereNotNull('design_code')
            ->where('design_code', '!=', '')
            ->orderByRaw('CAST(design_code AS UNSIGNED) DESC')
            ->value('design_code');
        
        if (!$latestCode) {
            // Start with this pattern based on existing codes
            return '09200001';
        }
        
        // Extract numeric part and increment
        $numericPart = (int) $latestCode;
        $nextCode = str_pad($numericPart + 1, 8, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        while (self::where('design_code', $nextCode)->exists()) {
            $nextCode = str_pad((int)$nextCode + 1, 8, '0', STR_PAD_LEFT);
        }
        
        return $nextCode;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
