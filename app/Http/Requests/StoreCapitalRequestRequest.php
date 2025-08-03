<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCapitalRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('outlet');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'purpose' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Capital amount is required.',
            'amount.numeric' => 'Capital amount must be a valid number.',
            'amount.min' => 'Capital amount must be greater than 0.',
            'amount.max' => 'Capital amount cannot exceed 999,999,999.99.',
            'purpose.required' => 'Purpose of the capital request is required.',
            'purpose.max' => 'Purpose cannot exceed 1000 characters.',
        ];
    }
}