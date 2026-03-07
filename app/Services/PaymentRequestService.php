<?php

namespace App\Services;

use App\Models\PaymentRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PaymentRequestService
{
    public function create(array $payload): PaymentRequest
    {
        return DB::transaction(function () use ($payload) {
            $items = Arr::pull($payload, 'items', []);

            $paymentRequest = PaymentRequest::query()->create($payload);
            $this->syncItems($paymentRequest, $items);

            return $paymentRequest->refresh();
        });
    }

    public function update(PaymentRequest $paymentRequest, array $payload): PaymentRequest
    {
        return DB::transaction(function () use ($paymentRequest, $payload) {
            $items = Arr::pull($payload, 'items', []);

            $paymentRequest->update($payload);
            $paymentRequest->items()->delete();
            $this->syncItems($paymentRequest, $items);

            return $paymentRequest->refresh();
        });
    }

    public function submit(PaymentRequest $paymentRequest, int $userId): PaymentRequest
    {
        $paymentRequest->update([
            'status' => 'submitted',
            'verification_status' => 'under_verification',
            'approval_status' => 'waiting_approval',
            'submitted_at' => now(),
            'updated_by' => $userId,
        ]);

        return $paymentRequest->refresh();
    }


    public function markAsPaid(PaymentRequest $paymentRequest, int $userId, string $paymentMethod, string $sourceAccount): PaymentRequest
    {
        $paymentRequest->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod,
            'source_account' => $sourceAccount,
            'paid_at' => now(),
            'updated_by' => $userId,
        ]);

        return $paymentRequest->refresh();
    }

    private function syncItems(PaymentRequest $paymentRequest, array $items): void
    {
        $totals = ['total' => 0, 'tax' => 0, 'net' => 0];

        foreach ($items as $item) {
            $amount = (float) ($item['qty'] ?? 1) * (float) ($item['unit_price'] ?? 0);
            $taxAmount = (float) ($item['tax_amount'] ?? 0);
            $netAmount = $amount + $taxAmount;

            $createdItem = $paymentRequest->items()->create([
                'category_id' => $item['category_id'] ?? null,
                'partner_id' => $item['partner_id'] ?? null,
                'description' => $item['description'],
                'qty' => $item['qty'] ?? 1,
                'unit_price' => $item['unit_price'] ?? 0,
                'amount' => $amount,
                'tax_code_id' => $item['tax_code_id'] ?? null,
                'tax_amount' => $taxAmount,
                'net_amount' => $netAmount,
                'reference_type' => $item['reference_type'] ?? null,
                'reference_id' => $item['reference_id'] ?? null,
            ]);

            $allocations = $item['allocations'] ?? [
                [
                    'cost_center_id' => $paymentRequest->cost_center_id,
                    'project_id' => $paymentRequest->project_id,
                    'amount' => $amount,
                ],
            ];

            foreach ($allocations as $allocation) {
                $createdItem->allocations()->create([
                    'cost_center_id' => $allocation['cost_center_id'] ?? null,
                    'project_id' => $allocation['project_id'] ?? null,
                    'amount' => $allocation['amount'] ?? 0,
                ]);
            }

            $totals['total'] += $amount;
            $totals['tax'] += $taxAmount;
            $totals['net'] += $netAmount;
        }

        $paymentRequest->update([
            'total_amount' => $totals['total'],
            'tax_amount' => $totals['tax'],
            'net_amount' => $totals['net'],
        ]);
    }
}
