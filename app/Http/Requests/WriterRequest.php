<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WriterRequest extends FormRequest
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
        if($this->method() == "PATCH")
        {
            return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'portrait' => 'nullable|image|max:2048',
            ];
        }
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'portrait' => 'nullable|image|max:2048',
        ];
    }
}
