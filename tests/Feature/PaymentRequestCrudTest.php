<?php

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

it('can perform payment request crud', function () {
    $user = User::factory()->create();

    $company = Company::query()->create([
        'code' => 'CMP-001',
        'name' => 'PT Finansia',
        'status' => 'active',
    ]);

    $branch = Branch::query()->create([
        'company_id' => $company->id,
        'code' => 'BR-001',
        'name' => 'Kantor Jakarta',
        'status' => 'active',
    ]);

    $department = Department::query()->create([
        'company_id' => $company->id,
        'code' => 'DEP-001',
        'name' => 'Finance',
        'status' => 'active',
    ]);

    $costCenter = CostCenter::query()->create([
        'company_id' => $company->id,
        'department_id' => $department->id,
        'code' => 'CC-001',
        'name' => 'Finance Ops',
        'status' => 'active',
    ]);

    $project = Project::query()->create([
        'company_id' => $company->id,
        'code' => 'PRJ-001',
        'name' => 'ERP Rollout',
        'status' => 'active',
    ]);

    $currency = Currency::query()->create([
        'code' => 'IDR',
        'name' => 'Rupiah',
        'symbol' => 'Rp',
        'is_base_currency' => true,
        'status' => 'active',
    ]);

    $category = TransactionCategory::query()->create([
        'company_id' => $company->id,
        'code' => 'TRX-OPS',
        'name' => 'Operational Expense',
        'flow_type' => 'outflow',
        'status' => 'active',
    ]);

    $partner = BusinessPartner::query()->create([
        'company_id' => $company->id,
        'code' => 'VND-001',
        'type' => 'vendor',
        'name' => 'PT Vendor Maju',
        'status' => 'active',
    ]);

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.store'), [
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'cost_center_id' => $costCenter->id,
            'project_id' => $project->id,
            'requester_id' => $user->id,
            'request_no' => 'PR-2026-0001',
            'request_date' => '2026-03-08',
            'priority' => 'normal',
            'due_date' => '2026-03-15',
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'description' => 'Payment internet office',
            'document_complete_flag' => true,
            'items' => [
                [
                    'description' => 'Invoice Internet Maret',
                    'qty' => 2,
                    'unit_price' => 250000,
                    'tax_amount' => 50000,
                    'category_id' => $category->id,
                    'partner_id' => $partner->id,
                    'allocations' => [
                        [
                            'cost_center_id' => $costCenter->id,
                            'project_id' => $project->id,
                            'amount' => 500000,
                        ],
                    ],
                ],
            ],
        ])
        ->assertRedirect();

    $paymentRequest = PaymentRequest::query()->first();

    expect($paymentRequest)->not->toBeNull();
    expect($paymentRequest->request_no)->toBe('PR-2026-0001');
    expect((float) $paymentRequest->total_amount)->toBe(500000.0);
    expect((float) $paymentRequest->tax_amount)->toBe(50000.0);
    expect((float) $paymentRequest->net_amount)->toBe(550000.0);
    expect($paymentRequest->items)->toHaveCount(1);
    expect($paymentRequest->items->first()->allocations)->toHaveCount(1);

    $this->actingAs($user)
        ->put(route('apps.cash-management.payment-requests.update', $paymentRequest), [
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'cost_center_id' => $costCenter->id,
            'project_id' => $project->id,
            'requester_id' => $user->id,
            'request_no' => 'PR-2026-0001',
            'request_date' => '2026-03-09',
            'priority' => 'high',
            'due_date' => '2026-03-18',
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'description' => 'Payment internet office updated',
            'document_complete_flag' => false,
            'items' => [
                [
                    'description' => 'Invoice Internet April',
                    'qty' => 1,
                    'unit_price' => 300000,
                    'tax_amount' => 30000,
                    'category_id' => $category->id,
                    'partner_id' => $partner->id,
                    'allocations' => [
                        [
                            'cost_center_id' => $costCenter->id,
                            'project_id' => $project->id,
                            'amount' => 300000,
                        ],
                    ],
                ],
            ],
        ])
        ->assertRedirect();

    $paymentRequest->refresh();

    expect($paymentRequest->priority)->toBe('high');
    expect($paymentRequest->description)->toBe('Payment internet office updated');
    expect((float) $paymentRequest->total_amount)->toBe(300000.0);
    expect((float) $paymentRequest->tax_amount)->toBe(30000.0);
    expect((float) $paymentRequest->net_amount)->toBe(330000.0);
    expect($paymentRequest->items()->count())->toBe(1);
    expect($paymentRequest->items()->first()->allocations()->count())->toBe(1);

    $this->actingAs($user)
        ->delete(route('apps.cash-management.payment-requests.destroy', $paymentRequest))
        ->assertRedirect();

    $this->assertSoftDeleted('payment_requests', ['id' => $paymentRequest->id]);
});

it('can mark payment request as paid from treasury execution', function () {
    $user = User::factory()->create();

    $company = Company::query()->create([
        'code' => 'CMP-002',
        'name' => 'PT Treasury Test',
        'status' => 'active',
    ]);

    $currency = Currency::query()->create([
        'code' => 'IDT',
        'name' => 'Rupiah Test',
        'symbol' => 'Rp',
        'is_base_currency' => false,
        'status' => 'active',
    ]);

    $paymentRequest = PaymentRequest::query()->create([
        'company_id' => $company->id,
        'branch_id' => null,
        'department_id' => null,
        'cost_center_id' => null,
        'project_id' => null,
        'requester_id' => $user->id,
        'request_no' => 'PR-2026-0901',
        'request_date' => now()->toDateString(),
        'priority' => 'normal',
        'due_date' => now()->addDays(3)->toDateString(),
        'currency_id' => $currency->id,
        'exchange_rate' => 1,
        'total_amount' => 100000,
        'tax_amount' => 0,
        'net_amount' => 100000,
        'description' => 'Treasury execution test',
        'status' => 'approved',
        'verification_status' => 'verified',
        'approval_status' => 'approved',
        'payment_status' => 'unpaid',
        'document_complete_flag' => true,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.mark-paid', $paymentRequest), [
            'payment_method' => 'Transfer Bank',
            'source_account' => 'Bank BCA Operasional',
        ])
        ->assertRedirect();

    $paymentRequest->refresh();

    expect($paymentRequest->status)->toBe('paid');
    expect($paymentRequest->payment_status)->toBe('paid');
    expect($paymentRequest->payment_method)->toBe('Transfer Bank');
    expect($paymentRequest->source_account)->toBe('Bank BCA Operasional');
    expect($paymentRequest->paid_at)->not->toBeNull();
});

it('can run approval workflow actions from backend endpoints', function () {
    $user = User::factory()->create();

    $company = Company::query()->create([
        'code' => 'CMP-003',
        'name' => 'PT Approval Test',
        'status' => 'active',
    ]);

    $currency = Currency::query()->create([
        'code' => 'IDU',
        'name' => 'Rupiah Uji',
        'symbol' => 'Rp',
        'is_base_currency' => false,
        'status' => 'active',
    ]);

    $makePaymentRequest = function (string $requestNo, string $status, string $verificationStatus, string $approvalStatus) use ($company, $currency, $user): PaymentRequest {
        return PaymentRequest::query()->create([
            'company_id' => $company->id,
            'branch_id' => null,
            'department_id' => null,
            'cost_center_id' => null,
            'project_id' => null,
            'requester_id' => $user->id,
            'request_no' => $requestNo,
            'request_date' => now()->toDateString(),
            'priority' => 'normal',
            'due_date' => now()->addDays(2)->toDateString(),
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'total_amount' => 250000,
            'tax_amount' => 0,
            'net_amount' => 250000,
            'description' => 'Approval workflow test',
            'status' => $status,
            'verification_status' => $verificationStatus,
            'approval_status' => $approvalStatus,
            'payment_status' => 'unpaid',
            'document_complete_flag' => true,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
    };

    $paymentRequest = $makePaymentRequest('PR-2026-1001', 'submitted', 'under_verification', 'waiting_approval');

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.verify', $paymentRequest))
        ->assertRedirect();

    $paymentRequest->refresh();

    expect($paymentRequest->status)->toBe('waiting_approval');
    expect($paymentRequest->verification_status)->toBe('verified');
    expect($paymentRequest->approval_status)->toBe('waiting_approval');
    expect($paymentRequest->verified_by)->toBe($user->id);
    expect($paymentRequest->verified_at)->not->toBeNull();

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.approve', $paymentRequest))
        ->assertRedirect();

    $paymentRequest->refresh();

    expect($paymentRequest->status)->toBe('approved');
    expect($paymentRequest->approval_status)->toBe('approved');
    expect($paymentRequest->approved_by)->toBe($user->id);
    expect($paymentRequest->approved_at)->not->toBeNull();

    $rejectionCandidate = $makePaymentRequest('PR-2026-1002', 'waiting_approval', 'verified', 'waiting_approval');

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.reject', $rejectionCandidate), [
            'reason' => 'Lampiran invoice tidak valid',
        ])
        ->assertRedirect();

    $rejectionCandidate->refresh();

    expect($rejectionCandidate->status)->toBe('rejected');
    expect($rejectionCandidate->approval_status)->toBe('rejected');
    expect($rejectionCandidate->rejected_reason)->toBe('Lampiran invoice tidak valid');

    $revisionCandidate = $makePaymentRequest('PR-2026-1003', 'waiting_approval', 'verified', 'waiting_approval');

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.request-revision', $revisionCandidate), [
            'reason' => 'Perbaiki nominal PPN dan dokumen pendukung',
        ])
        ->assertRedirect();

    $revisionCandidate->refresh();

    expect($revisionCandidate->status)->toBe('revision_required');
    expect($revisionCandidate->approval_status)->toBe('revision_required');
    expect($revisionCandidate->revision_no)->toBe(1);
    expect($revisionCandidate->rejected_reason)->toBe('Perbaiki nominal PPN dan dokumen pendukung');

    $this->actingAs($user)
        ->post(route('apps.cash-management.payment-requests.submit', $revisionCandidate))
        ->assertRedirect();

    $revisionCandidate->refresh();

    expect($revisionCandidate->status)->toBe('submitted');
    expect($revisionCandidate->verification_status)->toBe('under_verification');
    expect($revisionCandidate->approval_status)->toBe('waiting_approval');
    expect($revisionCandidate->rejected_reason)->toBeNull();
});
