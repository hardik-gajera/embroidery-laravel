<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile_no' => 'required|string|max:15',
            'downloaded_design' => 'required|integer|min:0',
            'total_design' => 'required|integer|min:0',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
