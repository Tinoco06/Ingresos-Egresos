<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id,user_id,' . auth()->id(),  //Verifica que el proyecto pertenezca al usuario autenticado
            'type' => 'required|in:ingreso,egreso',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
        ];
    }

    /**
     * Mensajes personalizados de validación
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'Debes seleccionar un proyecto para esta transacción.',
            'project_id.exists' => 'El proyecto seleccionado no existe.',
            'date.before_or_equal' => 'La fecha no puede ser posterior a hoy.',
        ];
    }
}
