<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterDataRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            return [
                'code' => 'required|string|max:50|unique:master_data,code',
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ];
        }

        return [
            'code' => 'required|string|max:50|unique:master_data,code,'. $this->master_datum->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode master data wajib diisi.',
            'code.unique' => 'Kode master data sudah digunakan.',
            'name.required' => 'Nama master data wajib diisi.',
            'category.required' => 'Kategori wajib diisi.',
            'is_active.required' => 'Status aktif wajib dipilih.',
        ];
    }
}
