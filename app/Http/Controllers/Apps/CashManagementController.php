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
                ['label' => 'Cash In Bulan Berjalan', 'value' => 'Rp 3.420.000.000'],
                ['label' => 'Cash Out Bulan Berjalan', 'value' => 'Rp 2.910.000.000'],
                ['label' => 'Net Cashflow', 'value' => 'Rp 510.000.000'],
                ['label' => 'Outstanding Approval', 'value' => '24 transaksi'],
                ['label' => 'Dokumen Belum Lengkap', 'value' => '7 transaksi'],
                ['label' => 'Selisih Rekonsiliasi', 'value' => 'Rp 4.250.000'],
            ],
            'cashflow' => [
                ['month' => 'Jan', 'in' => 1200, 'out' => 960],
                ['month' => 'Feb', 'in' => 1320, 'out' => 980],
                ['month' => 'Mar', 'in' => 980, 'out' => 910],
                ['month' => 'Apr', 'in' => 1240, 'out' => 1010],
                ['month' => 'May', 'in' => 1410, 'out' => 1180],
                ['month' => 'Jun', 'in' => 1510, 'out' => 1230],
            ],
            'pendingTasks' => [
                ['task' => 'Payment Request menunggu verifikasi', 'count' => 11],
                ['task' => 'Payment Request menunggu approval', 'count' => 8],
                ['task' => 'Transaksi overdue due date', 'count' => 5],
                ['task' => 'Unmatched bank reconciliation', 'count' => 4],
            ],
            'accountBalances' => [
                ['account' => 'Kas Besar - HO', 'type' => 'Cash', 'balance' => 'Rp 185.500.000'],
                ['account' => 'Bank BCA 00112233', 'type' => 'Bank', 'balance' => 'Rp 1.420.400.000'],
                ['account' => 'Bank Mandiri 77889900', 'type' => 'Bank', 'balance' => 'Rp 780.300.000'],
                ['account' => 'Petty Cash Cabang Surabaya', 'type' => 'Cash', 'balance' => 'Rp 27.800.000'],
            ],
        ]);
    }

    public function masterData(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Master Data',
            'description' => 'Data acuan untuk standarisasi proses finance dan konsistensi dokumen sesuai ISO 9001.',
            'modules' => [
                'Company & Branch',
                'Department & Cost Center',
                'Cash/Bank Accounts',
                'Partners (Vendor, Customer, Employee)',
                'Transaction Categories',
                'Tax Master',
                'Document Types',
                'Approval Matrix',
                'Currency & Exchange Rate',
            ],
            'keyControls' => [
                'Nomor dokumen otomatis dan konsisten',
                'Approval matrix berdasarkan nominal, departemen, dan jenis transaksi',
                'Validasi master data aktif/nonaktif',
            ],
        ]);
    }

    public function transactions(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Transactions',
            'description' => 'Pusat proses cash in/out dengan alur terdokumentasi dari draft sampai posting.',
            'modules' => [
                'Payment Request',
                'Cash In (Penerimaan)',
                'Cash Out (Pengeluaran)',
                'Petty Cash',
                'Transfer Antar Akun',
                'Adjustment / Reversal',
                'Recurring Transactions',
            ],
            'keyControls' => [
                'Status workflow standar: Draft → Submitted → Verified → Approved → Paid/Received → Posted',
                'Lampiran wajib untuk transaksi tertentu',
                'Mandatory reason untuk reject/cancel/adjustment',
            ],
        ]);
    }

    public function approvals(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Approvals',
            'description' => 'Memastikan segregation of duties antara maker, checker, approver, dan eksekutor.',
            'modules' => [
                'Need Verification',
                'Need Approval',
                'My Approvals',
                'Rejected / Revision Required',
                'Approval Timeline & Logs',
            ],
            'keyControls' => [
                'Dual control: maker tidak bisa menjadi final approver',
                'Approval by amount (limit supervisor/manager/director)',
                'Jejak keputusan approval dan alasan penolakan',
            ],
        ]);
    }

    public function treasury(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Treasury',
            'description' => 'Eksekusi pembayaran dan pengelolaan bukti transfer untuk menjaga ketertelusuran transaksi.',
            'modules' => [
                'Execute Payment',
                'Upload Transfer Proof',
                'Bank Charges',
                'Cheque / Giro Tracking',
            ],
            'keyControls' => [
                'Bukti transfer wajib sebelum posting',
                'Kontrol rekening sumber & tujuan',
                'Monitoring transaksi jatuh tempo',
            ],
        ]);
    }

    public function reconciliation(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Reconciliation',
            'description' => 'Mencocokkan transaksi sistem dengan mutasi bank serta kontrol kas fisik.',
            'modules' => [
                'Bank Reconciliation',
                'Cash Opname',
                'Unmatched Items',
                'Reconciliation Closing',
            ],
            'keyControls' => [
                'Import statement bank dan matching otomatis/manual',
                'Pencatatan selisih serta approval hasil rekonsiliasi',
                'Closing periode rekonsiliasi untuk mencegah perubahan tidak sah',
            ],
        ]);
    }

    public function reports(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Reports',
            'description' => 'Laporan operasional, kontrol, dan manajerial untuk evaluasi berkelanjutan.',
            'modules' => [
                'Cash Book & Bank Book',
                'Receipt & Payment Report',
                'Petty Cash Report',
                'Cashflow Report',
                'Approval History',
                'Audit Trail',
                'Outstanding Requests',
            ],
            'keyControls' => [
                'Filter laporan per branch, cost center, kategori, periode',
                'Konsistensi format untuk audit ISO 9001',
                'Pelacakan detail sampai dokumen pendukung',
            ],
        ]);
    }

    public function administration(): Response
    {
        return Inertia::render('Apps/CashManagement/ModulePage', [
            'title' => 'Administration',
            'description' => 'Pengaturan sistem untuk user, role, sequence dokumen, dan period closing.',
            'modules' => [
                'Users',
                'Roles & Permissions',
                'Document Numbering Format',
                'Period Closing',
                'System Settings',
                'Notification Template',
            ],
            'keyControls' => [
                'Periode tertutup tidak dapat diubah',
                'Konfigurasi numbering format otomatis',
                'Akses read-only untuk auditor/internal control',
            ],
        ]);
    }
}
