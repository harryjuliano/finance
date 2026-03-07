<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
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
        return Inertia::render('Apps/CashManagement/Treasury/Index', [
            'summary' => [
                ['label' => 'Ready to Execute', 'value' => '12 request', 'status_filter' => 'ready'],
                ['label' => 'Queued in Bank Portal', 'value' => '4 request', 'status_filter' => 'queued'],
                ['label' => 'Executed Today', 'value' => '7 request', 'status_filter' => 'paid'],
                ['label' => 'Total Amount Today', 'value' => 'Rp 438.750.000', 'status_filter' => null],
            ],
            'executionQueue' => [
                [
                    'request_no' => 'PR-2026-03-018',
                    'vendor' => 'PT Sumber Logistik Nusantara',
                    'due_date' => '08 Mar 2026',
                    'payment_method' => 'Bank Transfer (BCA)',
                    'source_account' => 'BCA Operasional - 0912233445',
                    'amount' => 'Rp 125.000.000',
                    'status' => 'ready',
                    'status_label' => 'Ready',
                ],
                [
                    'request_no' => 'PR-2026-03-017',
                    'vendor' => 'CV Prima Teknologi Kantor',
                    'due_date' => '08 Mar 2026',
                    'payment_method' => 'Virtual Account',
                    'source_account' => 'Mandiri AP - 1400099112',
                    'amount' => 'Rp 48.750.000',
                    'status' => 'queued',
                    'status_label' => 'Queued',
                ],
                [
                    'request_no' => 'PR-2026-03-015',
                    'vendor' => 'PT Inti Energi Distribusi',
                    'due_date' => '09 Mar 2026',
                    'payment_method' => 'RTGS',
                    'source_account' => 'BCA Operasional - 0912233445',
                    'amount' => 'Rp 210.000.000',
                    'status' => 'ready',
                    'status_label' => 'Ready',
                ],
                [
                    'request_no' => 'PR-2026-03-011',
                    'vendor' => 'PT Karya Bersama Catering',
                    'due_date' => '07 Mar 2026',
                    'payment_method' => 'Transfer Online',
                    'source_account' => 'BCA Operasional - 0912233445',
                    'amount' => 'Rp 8.500.000',
                    'status' => 'paid',
                    'status_label' => 'Paid',
                ],
            ],
            'recentExecutions' => [
                [
                    'reference' => 'PAY-2026-03-0094',
                    'time' => '07 Mar 2026 10:14',
                    'bank_channel' => 'BCA Corporate KlikBCA',
                    'amount' => 'Rp 18.200.000',
                ],
                [
                    'reference' => 'PAY-2026-03-0093',
                    'time' => '07 Mar 2026 09:58',
                    'bank_channel' => 'Mandiri Cash Management',
                    'amount' => 'Rp 42.500.000',
                ],
                [
                    'reference' => 'PAY-2026-03-0091',
                    'time' => '07 Mar 2026 09:31',
                    'bank_channel' => 'BCA Corporate KlikBCA',
                    'amount' => 'Rp 77.000.000',
                ],
            ],
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
