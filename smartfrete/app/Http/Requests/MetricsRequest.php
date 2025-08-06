<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Classe responsável por validar os parâmetros da requisição de métricas.
 */
class MetricsRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para os parâmetros da requisição.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'last_quotes' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Mensagens personalizadas para erros de validação.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'last_quotes.integer' => 'O parâmetro last_quotes deve ser um número inteiro.',
            'last_quotes.min' => 'O parâmetro last_quotes deve ser no mínimo 1.',
        ];
    }

    /**
     * Manipula falhas de validação lançando uma exceção HTTP com os erros.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Erro de validação.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
