<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $this->route('customer')->id,
            'mobile_no' => 'required|string|max:15',
            'downloaded_design' => 'required|integer|min:0',
            'total_design' => 'required|integer|min:0',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
