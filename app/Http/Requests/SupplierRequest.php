<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
        $rules = [
            'nama' => [
                'required',
                'string',
                'max:50',
            ],
            'telepon' => [
                'required', 
                'string',
                'max:20',
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'string',
                'max:100',
            ],
            'alamat' => [
                'required',
                'string',
                'max:200',
            ],
        ];

        if ($this->isMethod('post')) {
            $rules['nama'][] = 'unique:suppliers,nama';
            $rules['telepon'][] = 'unique:suppliers,telepon';
            $rules['email'][] = 'unique:suppliers,email';
        } else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['nama'][] = Rule::unique('suppliers', 'nama')->ignore($this->route('supplier'));
            $rules['telepon'][] = Rule::unique('suppliers', 'telepon')->ignore($this->route('supplier'));
            $rules['email'][] = Rule::unique('suppliers', 'email')->ignore($this->route('supplier'));
        }

        return $rules;
    }
}
