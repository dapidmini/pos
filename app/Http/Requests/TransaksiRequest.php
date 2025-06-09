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
                'numeric',
                'min:0'
            ],
            'total' => [
                'required',
                'numeric',
                'min:0'
            ],
            // validasi untuk detail transaksi ditaruh di sini
            'details' => [
                'required',
                'array',
                'min:1',
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
            'details.*.diskon' => [
                'nullable',
                'integer',
            ],
            'details.*.catatan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $requestData = $this->all();
        $detailsData = [];
        $calculatedGrandTotal = 0;

        if (isset($requestData['product_id']) && is_array($requestData['product_id'])) {
            $numDetails = count($requestData['product_id']);

            for ($i=0; $i < $numDetails; $i++) { 
                // pastikan setiap elemen array yg akan dipakai bisa diakses
                $productId = $requestData['product_id'][$i] ?? null;
                $jumlah = $requestData['jumlah'][$i] ?? 0;
                $harga = $requestData['harga'][$i] ?? 0;
                $catatan = $requestData['catatan'][$i] ?? '';
                $diskonItem = $requestData['diskon'][$i] ?? 0;

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
                $calculatedGrandTotal += $calculatedSubtotal;
            }
            // end for
        }
        // end if (isset($requestData['product_id']) && is_array($requestData['product_id'])) 

        $diskonGlobal = (int)($requestData['diskon'] ?? 0);
        $finalTotal = $calculatedGrandTotal - $diskonGlobal;
        if ($finalTotal < 0) {
            $finalTotal = 0;
        }

        $this->merge([
            'details' => $detailsData,
            'total' => (int)$finalTotal,
            'diskon' => (int)$diskonGlobal,
        ]);
    }
}
