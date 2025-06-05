<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'id_kategori' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'harga_beli' => [
                'required',
                'integer',
                'min:0',
            ],
            'harga_jual' => [
                'required',
                'integer',
                'min:0',
            ],
            'status' => [
                'required',
                'boolean',
            ],
            'id_supplier' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],
            'satuan' => [ // <-- Aturan untuk satuan
                'required',
                'string',
                'max:20', // Sesuaikan dengan migrasi
            ],
        ];

        if ($this->isMethod('post')) {
            $rules['nama'][] = 'unique:products,nama';
        } else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['nama'][] = Rule::unique('products', 'nama')->ignore($this->route('product'));
        }

        return $rules;
    }
}
