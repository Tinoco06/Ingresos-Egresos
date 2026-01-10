<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            // Validaciones de projects
            'name' => 'required|string|max:250',
            'description' => 'nullable|string|max:500',
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del proyecto es obligatorio.',
            'name.max' => 'El nombre no debe exceder 255 caracteres.',
            'description.max' => 'La descripción no debe exceder 500 caracteres.',
        ];
    }
}
