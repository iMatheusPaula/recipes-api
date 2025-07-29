<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:50',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ];
    }

    /**
     * Validates if the user has already evaluated the recipe before allowing a new review.
     *
     * @return array<Closure>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $recipe = $this->route('recipe');
                $hasAlreadyEvaluated = $recipe->reviews()->where('ip_address', $this->ip())->exists();

                if ($hasAlreadyEvaluated) {
                    $validator->errors()->add('review', 'Você já avaliou esta receita.');
                }
            },
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser uma string válida.',
            'name.max' => 'O nome não pode ter mais de 50 caracteres.',
            'rating.required' => 'A avaliação é obrigatória.',
            'rating.integer' => 'A avaliação deve ser um número inteiro.',
            'rating.between' => 'A avaliação deve estar entre 1 e 5.',
            'comment.string' => 'O comentário deve ser uma string válida.',
        ];
    }
}
