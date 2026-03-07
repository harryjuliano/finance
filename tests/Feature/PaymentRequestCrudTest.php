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
