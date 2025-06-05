<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
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
            'kode_invoice' => [
                'required',
                'string',
                'max:20',
            ],
            'tanggal' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'nama_customer' => [
                'required',
                'string',
                'max:50',
            ],
            'meja' => [
                'required',
                'string',
                'max:20',
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:200',
            ],
            'diskon' => [
                'nullable',
                'string',
                'max:12'
            ],
            // validasi untuk detail transaksi ditaruh di sini
            'details' => [
                'required',
                'array',
                'min:1',
            ],
            'details.*.id' => [
                'nullable',
                'integer',
                'exists:detail_transaksis,id',
            ],
            'details.*.product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
            'details.*.jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
            'details.*.harga' => [
                'required',
                'integer',
                'min:0',
            ],
            'details.*.catatan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];

        return $rules;
    }
}
