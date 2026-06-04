<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'number_of_design' => 'required|integer|min:1',
            'time_period' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'state' => 'required|in:draft,confirm,finish',
            'package_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
