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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
