<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentRequestId = $this->payment_request?->id;

        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'cost_center_id' => ['nullable', 'integer', 'exists:cost_centers,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'requester_id' => ['required', 'integer', 'exists:users,id'],
            'request_no' => ['required', 'string', 'max:100', 'unique:payment_requests,request_no,'.$paymentRequestId],
            'request_date' => ['required', 'date'],
            'priority' => ['required', 'string', 'in:low,normal,high,urgent'],
            'due_date' => ['nullable', 'date'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001'],
            'description' => ['nullable', 'string'],
            'document_complete_flag' => ['boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.category_id' => ['nullable', 'integer', 'exists:transaction_categories,id'],
            'items.*.partner_id' => ['nullable', 'integer', 'exists:business_partners,id'],
            'items.*.allocations' => ['nullable', 'array', 'min:1'],
            'items.*.allocations.*.cost_center_id' => ['nullable', 'integer', 'exists:cost_centers,id'],
            'items.*.allocations.*.project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'items.*.allocations.*.amount' => ['required_with:items.*.allocations', 'numeric', 'min:0.01'],
        ];
    }
}
