<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDesignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'design_code' => 'nullable|string|max:100',
            'emb_file' => 'nullable|file|max:10240',
            'stitches' => 'nullable|integer|min:0',
            'height' => 'nullable|string|max:50',
            'width' => 'nullable|string|max:50',
            'area' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'needle_color' => 'nullable|string|max:255',
            'design_format' => 'nullable|string|max:50',
            'design_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'design_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }
}
