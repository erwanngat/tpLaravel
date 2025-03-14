<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisheRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
