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
            'recipient.zipcode' => 'required|string|size:8',

            'dispatchers' => 'required|array|min:1',
            'dispatchers.*.volumes' => 'required|array|min:1',

            'dispatchers.*.volumes.*.category' => 'required|integer',
            'dispatchers.*.volumes.*.amount' => 'required|integer|min:1',
            'dispatchers.*.volumes.*.unitary_weight' => 'required|numeric|min:0.01',
            'dispatchers.*.volumes.*.unitary_price' => 'required|numeric|min:0',
            'dispatchers.*.volumes.*.sku' => 'required|string|max:255',
            'dispatchers.*.volumes.*.height' => 'required|numeric|min:0.01',
            'dispatchers.*.volumes.*.width' => 'required|numeric|min:0.01',
            'dispatchers.*.volumes.*.length' => 'required|numeric|min:0.01',

            'simulation_type' => 'required|array|min:1',
            'simulation_type.*' => 'in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient.zipcode.required' => 'O campo CEP é obrigatório.',
            'recipient.zipcode.string' => 'O CEP deve ser uma string.',
            'recipient.zipcode.size' => 'O CEP deve conter exatamente 8 caracteres.',

            'dispatchers.required' => 'Pelo menos um expedidor deve ser informado.',
            'dispatchers.array' => 'O campo dispatchers deve ser um array.',

            'dispatchers.*.volumes.required' => 'Pelo menos um volume deve ser informado.',
            'dispatchers.*.volumes.array' => 'O campo volumes deve ser um array.',

            'dispatchers.*.volumes.*.category.required' => 'A categoria do volume é obrigatória.',
            'dispatchers.*.volumes.*.category.integer' => 'A categoria do volume deve ser um número inteiro.',

            'dispatchers.*.volumes.*.amount.required' => 'A quantidade é obrigatória.',
            'dispatchers.*.volumes.*.amount.integer' => 'A quantidade deve ser um número inteiro.',
            'dispatchers.*.volumes.*.amount.min' => 'A quantidade mínima permitida é 1.',

            'dispatchers.*.volumes.*.unitary_weight.required' => 'O peso unitário é obrigatório.',
            'dispatchers.*.volumes.*.unitary_weight.numeric' => 'O peso unitário deve ser um número.',
            'dispatchers.*.volumes.*.unitary_weight.min' => 'O peso unitário deve ser maior que zero.',

            'dispatchers.*.volumes.*.unitary_price.required' => 'O preço é obrigatório.',
            'dispatchers.*.volumes.*.unitary_price.numeric' => 'O preço deve ser um número.',
            'dispatchers.*.volumes.*.unitary_price.min' => 'O preço não pode ser negativo.',

            'dispatchers.*.volumes.*.sku.required' => 'O SKU é obrigatório.',
            'dispatchers.*.volumes.*.sku.string' => 'O SKU deve ser uma string.',
            'dispatchers.*.volumes.*.sku.max' => 'O SKU pode ter no máximo 255 caracteres.',

            'dispatchers.*.volumes.*.height.required' => 'A altura é obrigatória.',
            'dispatchers.*.volumes.*.height.numeric' => 'A altura deve ser um número.',
            'dispatchers.*.volumes.*.height.min' => 'A altura deve ser maior que zero.',

            'dispatchers.*.volumes.*.width.required' => 'A largura é obrigatória.',
            'dispatchers.*.volumes.*.width.numeric' => 'A largura deve ser um número.',
            'dispatchers.*.volumes.*.width.min' => 'A largura deve ser maior que zero.',

            'dispatchers.*.volumes.*.length.required' => 'O comprimento é obrigatório.',
            'dispatchers.*.volumes.*.length.numeric' => 'O comprimento deve ser um número.',
            'dispatchers.*.volumes.*.length.min' => 'O comprimento deve ser maior que zero.',

            'simulation_type.required' => 'O tipo de simulação é obrigatório.',
            'simulation_type.array' => 'O tipo de simulação deve ser um array.',
            'simulation_type.*.in' => 'O tipo de simulação deve ser 0 (Fracionada) ou 1 (Lotação).',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
