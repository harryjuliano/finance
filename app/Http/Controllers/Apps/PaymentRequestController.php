<?php

namespace App\Http\Controllers\Apps;

use App\Actions\PaymentRequest\SubmitPaymentRequestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequestRequest;
use App\Models\PaymentRequest;
use App\Services\PaymentRequestService;
use Inertia\Inertia;
use Inertia\Response;

class PaymentRequestController extends Controller
{
    public function __construct(private readonly PaymentRequestService $paymentRequestService)
    {
    }

    public function index(): Response
    {
        $paymentRequests = PaymentRequest::query()
            ->with('requester:id,name')
            ->when(request('search'), function ($query, string $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('request_no', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('request_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Apps/CashManagement/PaymentRequests/Index', [
            'paymentRequests' => $paymentRequests,
            'workflowStatuses' => [
                'draft', 'submitted', 'under_verification', 'verified', 'waiting_approval',
                'approved', 'rejected', 'revision_required', 'ready_to_pay', 'paid', 'posted',
                'cancelled', 'closed',
            ],
            'priorities' => ['low', 'normal', 'high', 'urgent'],
        ]);
    }

    public function store(PaymentRequestRequest $request)
    {
        $payload = $request->validated();
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;

        $this->paymentRequestService->create($payload);

        return back();
    }

    public function update(PaymentRequestRequest $request, PaymentRequest $payment_request)
    {
        $payload = $request->validated();
        $payload['updated_by'] = $request->user()->id;

        $this->paymentRequestService->update($payment_request, $payload);

        return back();
    }

    public function destroy(PaymentRequest $payment_request)
    {
        $payment_request->delete();

        return back();
    }

    public function submit(PaymentRequest $payment_request, SubmitPaymentRequestAction $action)
    {
        $action->execute($payment_request, auth()->id());

        return back();
    }
}
