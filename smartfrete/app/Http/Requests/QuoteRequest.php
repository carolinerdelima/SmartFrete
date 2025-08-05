<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient.address.zipcode' => 'required|string|size:8',
            'volumes' => 'required|array|min:1',
            'volumes.*.category' => 'required|integer',
            'volumes.*.amount' => 'required|integer|min:1',
            'volumes.*.unitary_weight' => 'required|numeric|min:0.01',
            'volumes.*.price' => 'required|numeric|min:0',
            'volumes.*.sku' => 'required|string|max:255',
            'volumes.*.height' => 'required|numeric|min:0.01',
            'volumes.*.width' => 'required|numeric|min:0.01',
            'volumes.*.length' => 'required|numeric|min:0.01',
            'simulation_type' => 'required|array|min:1',
            'simulation_type.*' => 'in:0,1',
        ];
    }

    /**
     * Mensagens personalizadas para erros de validação.
     */
    public function messages(): array
    {
        return [
            'recipient.address.zipcode.required' => 'O campo CEP é obrigatório.',
            'recipient.address.zipcode.string'   => 'O CEP deve ser uma string de 8 dígitos.',
            'recipient.address.zipcode.regex'    => 'O CEP deve conter exatamente 8 números.',

            'volumes.required' => 'Pelo menos um volume deve ser informado.',
            'volumes.array'    => 'O campo volumes deve ser um array.',

            'volumes.*.category.required'       => 'A categoria do volume é obrigatória.',
            'volumes.*.category.integer'        => 'A categoria do volume deve ser um número inteiro.',

            'volumes.*.amount.required'         => 'A quantidade é obrigatória.',
            'volumes.*.amount.integer'          => 'A quantidade deve ser um número inteiro.',
            'volumes.*.amount.min'              => 'A quantidade mínima permitida é 1.',

            'volumes.*.unitary_weight.required' => 'O peso unitário é obrigatório.',
            'volumes.*.unitary_weight.numeric'  => 'O peso unitário deve ser um número.',
            'volumes.*.unitary_weight.min'      => 'O peso unitário deve ser maior que zero.',

            'volumes.*.price.required'          => 'O preço é obrigatório.',
            'volumes.*.price.numeric'           => 'O preço deve ser um número.',
            'volumes.*.price.min'               => 'O preço não pode ser negativo.',

            'volumes.*.sku.required'            => 'O SKU é obrigatório.',
            'volumes.*.sku.string'              => 'O SKU deve ser uma string.',
            'volumes.*.sku.max'                 => 'O SKU pode ter no máximo 255 caracteres.',

            'volumes.*.height.required'         => 'A altura é obrigatória.',
            'volumes.*.height.numeric'          => 'A altura deve ser um número.',
            'volumes.*.height.min'              => 'A altura deve ser maior que zero.',

            'volumes.*.width.required'          => 'A largura é obrigatória.',
            'volumes.*.width.numeric'           => 'A largura deve ser um número.',
            'volumes.*.width.min'               => 'A largura deve ser maior que zero.',

            'volumes.*.length.required'         => 'O comprimento é obrigatório.',
            'volumes.*.length.numeric'          => 'O comprimento deve ser um número.',
            'volumes.*.length.min'              => 'O comprimento deve ser maior que zero.',

            'simulation_type.required' => 'O tipo de simulação é obrigatório.',
            'simulation_type.array' => 'O tipo de simulação deve ser um array.',
            'simulation_type.*.in' => 'O tipo de simulação deve ser 0 (Fracionada) ou 1 (Lotação).',
        ];
    }

    /**
     * Personaliza a resposta de erro de validação.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
