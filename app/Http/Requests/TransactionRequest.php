<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $transactionId = $this->transaction?->id;

        return [
            'reference_no' => 'required|string|max:100|unique:transactions,reference_no,'. $transactionId,
            'transaction_date' => 'required|date',
            'type' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'reference_no.required' => 'Nomor referensi wajib diisi.',
            'reference_no.unique' => 'Nomor referensi sudah digunakan.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'description.required' => 'Deskripsi transaksi wajib diisi.',
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'status.required' => 'Status transaksi wajib dipilih.',
        ];
    }
}
