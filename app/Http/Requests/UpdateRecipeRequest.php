<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'ingredients' => 'sometimes|required|string',
            'instructions' => 'sometimes|required|string'
        ];
    }

    /**
     * Get custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da receita é obrigatório.',
            'name.string' => 'O nome da receita deve ser uma string.',
            'name.max' => 'O nome da receita não pode exceder 255 caracteres.',
            'description.required' => 'A descrição da receita é obrigatória.',
            'description.string' => 'A descrição da receita deve ser uma string.',
            'ingredients.required' => 'Os ingredientes da receita são obrigatórios.',
            'ingredients.string' => 'Os ingredientes da receita devem ser uma string.',
            'instructions.required' => 'As instruções da receita são obrigatórias.',
            'instructions.string' => 'As instruções da receita devem ser uma string.'
        ];
    }
}
