<?php

namespace App\Http\Controllers\Apps;

use App\Actions\PaymentRequest\SubmitPaymentRequestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequestRequest;
use App\Models\Branch;
use App\Models\BusinessPartner;
use App\Models\Company;
use App\Models\CostCenter;
use App\Models\Currency;
use App\Models\Department;
use App\Models\PaymentRequest;
use App\Models\Project;
use App\Models\TransactionCategory;
use App\Models\User;
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
            ->with(['requester:id,name', 'items.allocations'])
            ->when(request('search'), function ($query, string $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('request_no', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->when(request('status'), function ($query, string $status) {
                $query->where('status', $status);
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
            'references' => [
                'companies' => Company::query()->select('id', 'name')->orderBy('name')->get(),
                'branches' => Branch::query()->select('id', 'name')->orderBy('name')->get(),
                'departments' => Department::query()->select('id', 'name')->orderBy('name')->get(),
                'costCenters' => CostCenter::query()->select('id', 'name')->orderBy('name')->get(),
                'projects' => Project::query()->select('id', 'name')->orderBy('name')->get(),
                'currencies' => Currency::query()->select('id', 'code', 'name')->orderBy('code')->get(),
                'requesters' => User::query()->select('id', 'name')->orderBy('name')->get(),
                'categories' => TransactionCategory::query()->select('id', 'name')->orderBy('name')->get(),
                'partners' => BusinessPartner::query()->select('id', 'name')->orderBy('name')->get(),
            ],
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
