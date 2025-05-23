<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'company' => 'nullable|string',
            'website' => 'nullable|string',
            'deal_stage' => 'nullable|string',
            'publisher_id' => 'nullable|integer',
            'role' => 'required|string|exists:roles,name'
        ];
    }
}
