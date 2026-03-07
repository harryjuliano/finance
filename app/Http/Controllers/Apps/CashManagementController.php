<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Inertia\Inertia;
use Inertia\Response;

class CashManagementController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Apps/CashManagement/Dashboard', [
            'kpis' => [
                ['label' => 'Total Cash Balance', 'value' => 'Rp 213.300.000'],
                ['label' => 'Total Bank Balance', 'value' => 'Rp 2.200.700.000'],
                ['label' => 'Today Inflow', 'value' => 'Rp 160.000.000'],
                ['label' => 'Today Outflow', 'value' => 'Rp 124.000.000'],
                ['label' => 'Pending Approvals', 'value' => '18 dokumen'],
                ['label' => 'Overdue Payments', 'value' => '6 dokumen'],
            ],
            'cashflow' => [
                ['month' => 'Jan', 'in' => 1350, 'out' => 980],
                ['month' => 'Feb', 'in' => 1480, 'out' => 1120],
                ['month' => 'Mar', 'in' => 1430, 'out' => 1240],
                ['month' => 'Apr', 'in' => 1560, 'out' => 1195],
                ['month' => 'May', 'in' => 1710, 'out' => 1330],
                ['month' => 'Jun', 'in' => 1820, 'out' => 1410],
            ],
            'pendingTasks' => [
                ['task' => 'Payment Request menunggu verifikasi', 'count' => 9],
                ['task' => 'Payment Request menunggu approval', 'count' => 6],
                ['task' => 'Dokumen compliance belum lengkap', 'count' => 3],
                ['task' => 'Unmatched bank statement', 'count' => 4],
            ],
            'accountBalances' => [
                ['account' => 'Kas Besar - Head Office', 'type' => 'cash', 'balance' => 'Rp 185.500.000'],
                ['account' => 'Bank BCA Operasional', 'type' => 'bank', 'balance' => 'Rp 1.420.400.000'],
                ['account' => 'Bank Mandiri Payroll', 'type' => 'bank', 'balance' => 'Rp 780.300.000'],
                ['account' => 'Petty Cash Cabang Surabaya', 'type' => 'cash', 'balance' => 'Rp 27.800.000'],
            ],
        ]);
    }

    public function approvals(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Approval Inbox',
            'description' => 'Segregation of duties maker-checker-approver dengan approval matrix per nominal.',
            'modules' => ['Need Verification', 'Need Approval', 'Rejected', 'Revision Required', 'Approval Timeline'],
            'keyControls' => ['Maker bukan final approver', 'Mandatory reason reject/revision', 'Audit trail semua keputusan'],
        ]);
    }

    public function treasury(): Response
    {
        $paymentRequests = PaymentRequest::query()
            ->with(['items.partner:id,name'])
            ->whereIn('status', [
                'submitted',
                'under_verification',
                'verified',
                'waiting_approval',
                'approved',
                'ready_to_pay',
                'paid',
            ])
            ->latest('due_date')
            ->latest('id')
            ->limit(20)
            ->get();

        $mapExecutionStatus = static function (PaymentRequest $paymentRequest): string {
            if ($paymentRequest->status === 'paid' || $paymentRequest->payment_status === 'paid') {
                return 'paid';
            }

            if (in_array($paymentRequest->status, ['approved', 'ready_to_pay'], true)) {
                return 'ready';
            }

            return 'queued';
        };

        $executionQueue = $paymentRequests->map(function (PaymentRequest $paymentRequest) use ($mapExecutionStatus) {
            $executionStatus = $mapExecutionStatus($paymentRequest);

            return [
                'request_no' => $paymentRequest->request_no,
                'vendor' => $paymentRequest->items->first()?->partner?->name ?? '-',
                'due_date' => optional($paymentRequest->due_date)->format('d M Y') ?? '-',
                'payment_method' => '-',
                'source_account' => '-',
                'amount' => 'Rp '.number_format((float) $paymentRequest->net_amount, 0, ',', '.'),
                'status' => $executionStatus,
                'status_label' => ucfirst($executionStatus),
            ];
        })->values();

        $readyCount = $paymentRequests->filter(fn (PaymentRequest $paymentRequest): bool => $mapExecutionStatus($paymentRequest) === 'ready')->count();
        $queuedCount = $paymentRequests->filter(fn (PaymentRequest $paymentRequest): bool => $mapExecutionStatus($paymentRequest) === 'queued')->count();
        $paidToday = $paymentRequests->filter(function (PaymentRequest $paymentRequest): bool {
            return ($paymentRequest->status === 'paid' || $paymentRequest->payment_status === 'paid')
                && optional($paymentRequest->updated_at)->isToday();
        });
        $paidTodayCount = $paidToday->count();
        $paidTodayAmount = $paidToday->sum(fn (PaymentRequest $paymentRequest): float => (float) $paymentRequest->net_amount);

        return Inertia::render('Apps/CashManagement/Treasury/Index', [
            'summary' => [
                ['label' => 'Ready to Execute', 'value' => "{$readyCount} request", 'status_filter' => 'approved'],
                ['label' => 'Queued in Process', 'value' => "{$queuedCount} request", 'status_filter' => 'submitted'],
                ['label' => 'Executed Today', 'value' => "{$paidTodayCount} request", 'status_filter' => 'paid'],
                ['label' => 'Total Amount Today', 'value' => 'Rp '.number_format((float) $paidTodayAmount, 0, ',', '.'), 'status_filter' => null],
            ],
            'executionQueue' => $executionQueue,
            'recentExecutions' => $paymentRequests
                ->filter(fn (PaymentRequest $paymentRequest): bool => $mapExecutionStatus($paymentRequest) === 'paid')
                ->take(3)
                ->map(fn (PaymentRequest $paymentRequest): array => [
                    'reference' => $paymentRequest->request_no,
                    'time' => optional($paymentRequest->updated_at)->format('d M Y H:i') ?? '-',
                    'bank_channel' => 'Bank channel belum diinput',
                    'amount' => 'Rp '.number_format((float) $paymentRequest->net_amount, 0, ',', '.'),
                ])
                ->values(),
        ]);
    }

    public function reconciliation(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Bank Reconciliation',
            'description' => 'Pencocokan bank statement vs transaksi sistem secara otomatis maupun manual.',
            'modules' => ['Statement Import', 'Auto Match', 'Manual Match', 'Unmatched Items', 'Finalize Reconciliation'],
            'keyControls' => ['Difference tracking', 'Approval hasil rekonsiliasi', 'Period close lock'],
        ]);
    }

    public function reports(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Finance Reports',
            'description' => 'Laporan operasional dan kontrol untuk KPI, compliance, dan audit readiness.',
            'modules' => ['Cash Position', 'Payment Aging', 'Approval History', 'Reconciliation Summary', 'Audit Trail'],
            'keyControls' => ['Filter multi branch', 'Export standar audit', 'Traceability sampai lampiran dokumen'],
        ]);
    }

    public function administration(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'System Administration',
            'description' => 'Pengelolaan data organisasi, numbering dokumen, role permission, dan period closing.',
            'modules' => ['Users', 'Roles', 'Document Types', 'Document Sequences', 'Period Closing'],
            'keyControls' => ['Period lock', 'Approval matrix setup', 'Read-only auditor role'],
        ]);
    }
}
