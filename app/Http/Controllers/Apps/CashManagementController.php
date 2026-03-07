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
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Payment Execution',
            'description' => 'Eksekusi pembayaran dari payment request ter-approve termasuk upload payment proof.',
            'modules' => ['Selected Requests', 'Source Accounts', 'Payment Method', 'Transfer Fee', 'Proof Upload'],
            'keyControls' => ['Bukti transfer wajib', 'Kontrol rekening sumber', 'Status paid & posted terpisah'],
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
