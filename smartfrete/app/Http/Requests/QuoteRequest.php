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
            'recipient.zipcode' => 'required|digits:8',
            'dispatchers' => 'required|array|min:1',
            'dispatchers.*.volumes' => 'required|array|min:1',

            'dispatchers.*.volumes.*.category' => 'required|string',
            'dispatchers.*.volumes.*.amount' => 'required|integer|min:1',
            'dispatchers.*.volumes.*.unitary_weight' => 'required|numeric|min:0.01',
            'dispatchers.*.volumes.*.unitary_price' => 'required|numeric|min:0',
            'dispatchers.*.volumes.*.sku' => 'nullable|string|max:255',
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
            'recipient.zipcode.digits' => 'O CEP deve conter exatamente 8 números.',

            'dispatchers.required' => 'Pelo menos um expedidor deve ser informado.',
            'dispatchers.array' => 'O campo dispatchers deve ser um array.',

            'dispatchers.*.volumes.required' => 'Pelo menos um volume deve ser informado para o expedidor.',
            'dispatchers.*.volumes.array' => 'O campo volumes deve ser um array.',

            'dispatchers.*.volumes.*.category.required' => 'A categoria do volume é obrigatória.',
            'dispatchers.*.volumes.*.amount.required' => 'A quantidade é obrigatória.',
            'dispatchers.*.volumes.*.amount.min' => 'A quantidade mínima é 1.',
            'dispatchers.*.volumes.*.unitary_weight.required' => 'O peso unitário é obrigatório.',
            'dispatchers.*.volumes.*.unitary_weight.min' => 'O peso unitário deve ser maior que zero.',
            'dispatchers.*.volumes.*.unitary_price.required' => 'O preço é obrigatório.',
            'dispatchers.*.volumes.*.unitary_price.min' => 'O preço não pode ser negativo.',
            'dispatchers.*.volumes.*.height.required' => 'A altura é obrigatória.',
            'dispatchers.*.volumes.*.width.required' => 'A largura é obrigatória.',
            'dispatchers.*.volumes.*.length.required' => 'O comprimento é obrigatório.',

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
