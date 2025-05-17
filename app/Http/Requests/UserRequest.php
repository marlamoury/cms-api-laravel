<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('user'),
            'password' => $this->isMethod('post') ? 'required|string|min:6' : 'nullable|string|min:6',
            'telefone' => 'required|string',
            'is_valid' => 'boolean',
        ];
    }
}
