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
            'tanggal' => [
                'required',
                'date',
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
            // // validasi untuk detail transaksi ditaruh di sini
            // 'details' => [
            //     'required',
            //     'array',
            //     'min:1',
            // ],
            // 'details.*.id' => [
            //     'nullable',
            //     'integer',
            //     'exists:detail_transaksis,id',
            // ],
            // 'details.*.product_id' => [
            //     'required',
            //     'integer',
            //     'exists:products,id',
            // ],
            // 'details.*.jumlah' => [
            //     'required',
            //     'integer',
            //     'min:1',
            // ],
            // 'details.*.harga' => [
            //     'required',
            //     'integer',
            //     'min:0',
            // ],
            // 'details.*.catatan' => [
            //     'nullable',
            //     'string',
            //     'max:255',
            // ],
        ];

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $requestData = $this->all();
        $detailsData = [];

        if (isset($requestData['product_id']) && is_array($requestData['product_id'])) {
            $numDetails = count($requestData['product_id']);

            for ($i=0; $i < $numDetails; $i++) { 
                // pastikan setiap elemen array yg akan dipakai bisa diakses
                $productId = $requestData['product_id'][$i] ?? null;
                $jumlah = $requestData['jumlah'][$i] ?? null;
                $harga = $requestData['harga'][$i] ?? null;
                $catatan = $requestData['catatan'][$i] ?? null;
                $diskonItem = $requestData['diskon'][$i] ?? null;

                $calculatedSubtotal = 0;
                if (is_numeric($jumlah) && is_numeric($harga)) {
                    $calculatedSubtotal = ($jumlah * $harga) - $diskonItem;
                }

                $detailsData[] = [
                    'product_id' => (int)$productId,
                    'jumlah' => (int)$jumlah,
                    'harga' => (int)$harga,
                    'subtotal' => (int)$calculatedSubtotal,
                    'catatan' => $catatan,
                    'diskon' => $diskonItem,
                ];
            }
            // end for
        }
        // end if (isset($requestData['product_id']) && is_array($requestData['product_id'])) 

        $this->merge(['details' => $detailsData]);
    }
}
