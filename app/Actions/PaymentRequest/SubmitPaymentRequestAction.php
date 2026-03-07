<?php

namespace App\Actions\PaymentRequest;

use App\Models\PaymentRequest;
use App\Services\PaymentRequestService;

class SubmitPaymentRequestAction
{
    public function __construct(private readonly PaymentRequestService $paymentRequestService)
    {
    }

    public function execute(PaymentRequest $paymentRequest, int $actorId): PaymentRequest
    {
        return $this->paymentRequestService->submit($paymentRequest, $actorId);
    }
}
