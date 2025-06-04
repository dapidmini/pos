<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        ];

        if ($this->isMethod('post')) {
            $rules['nama'][] = 'unique:categories,nama';
        } else if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['nama'][] = Rule::unique('categories', 'nama')->ignore($this->route('category'));
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori ini sudah ada.',
            'nama.max' => 'Nama kategori tidak boleh lebih dari :max karakter.',
        ];
    }
}
