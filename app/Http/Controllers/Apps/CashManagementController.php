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
                ['label' => 'Ringkasan Saldo Kas & Bank', 'value' => 'Rp 2.414.000.000'],
                ['label' => 'Total Payment Request Pending', 'value' => '18 dokumen'],
                ['label' => 'Total Pembayaran Hari Ini', 'value' => 'Rp 124.000.000'],
                ['label' => 'Cash Flow Bulan Berjalan', 'value' => 'In: Rp 1.820.000.000 / Out: Rp 1.410.000.000'],
                ['label' => 'Transaksi Menunggu Approval', 'value' => '12 transaksi'],
                ['label' => 'Rekonsiliasi Belum Selesai', 'value' => '4 akun'],
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
                ['task' => 'Buat Payment Request', 'count' => 7],
                ['task' => 'Input Penerimaan', 'count' => 5],
                ['task' => 'Execute Payment', 'count' => 6],
                ['task' => 'Approval Queue', 'count' => 12],
                ['task' => 'Rekonsiliasi belum selesai', 'count' => 4],
            ],
            'accountBalances' => [
                ['account' => 'Kas Besar - Head Office', 'type' => 'cash', 'balance' => 'Rp 185.500.000'],
                ['account' => 'Bank BCA Operasional', 'type' => 'bank', 'balance' => 'Rp 1.420.400.000'],
                ['account' => 'Bank Mandiri Payroll', 'type' => 'bank', 'balance' => 'Rp 780.300.000'],
                ['account' => 'Petty Cash Cabang Surabaya', 'type' => 'cash', 'balance' => 'Rp 27.800.000'],
            ],
        ]);
    }

    public function workspace(string $module): Response
    {
        $workspaces = [
            'master-data' => [
                'title' => 'Master Data',
                'description' => 'Semua referensi transaksi dikelola di sini untuk menjaga konsistensi input.',
                'modules' => ['Business Unit / Company', 'Departemen', 'Cost Center / Project', 'Vendor / Supplier', 'Customer', 'Bank & Cash Account', 'COA / Chart of Account', 'Transaction Category', 'Payment Method', 'Currency', 'Tax Master', 'Document Type', 'Attachment Category'],
                'keyControls' => ['Single source of truth master transaksi', 'Validasi data referensi sebelum transaksi', 'Mendukung audit trail ISO 9001'],
            ],
            'payment-request' => [
                'title' => 'Payment Request',
                'description' => 'Alur pengajuan pembayaran dari Draft sampai Ready for Payment.',
                'modules' => ['Semua Payment Request', 'Buat Payment Request', 'Draft Request', 'Menunggu Approval', 'Request Direvisi', 'Request Ditolak', 'Request Disetujui', 'Siap Dibayar', 'Riwayat Payment Request'],
                'keyControls' => ['Data inti: vendor, jumlah, kategori biaya, cost center', 'Data inti: due date, payment method, lampiran invoice', 'Workflow status: Draft, Submitted, Under Review, Approved, Rejected, Ready for Payment'],
            ],
            'approval' => [
                'title' => 'Approval',
                'description' => 'Pusat persetujuan untuk Supervisor, Finance Manager, dan Director.',
                'modules' => ['Menunggu Persetujuan Saya', 'Semua Approval', 'Approval Payment Request', 'Approval Kas Kecil', 'Approval Transfer', 'Approval Adjustment', 'Riwayat Approval'],
                'keyControls' => ['Aksi approve, reject, request revision', 'Queue approval terpusat lintas proses', 'Riwayat approval mendukung audit ISO 9001'],
            ],
            'execute-cash' => [
                'title' => 'Execute Cash',
                'description' => 'Treasury mengeksekusi pembayaran setelah approval dengan kontrol bukti bayar.',
                'modules' => ['Pembayaran Siap Dieksekusi', 'Execute Payment', 'Pembayaran Sebagian', 'Pembayaran Pending', 'Pembayaran Selesai', 'Bukti Pembayaran', 'Riwayat Pembayaran'],
                'keyControls' => ['Data pembayaran: rekening sumber, metode, referensi transfer', 'Data pembayaran: tanggal bayar dan upload bukti transfer', 'Status: Ready for Payment, Processing, Partially Paid, Paid'],
            ],
            'cash-receipt' => [
                'title' => 'Cash Receipt',
                'description' => 'Mencatat penerimaan kas dari customer, refund, dan transfer masuk.',
                'modules' => ['Semua Penerimaan', 'Input Penerimaan', 'Penerimaan Customer', 'Penerimaan Lainnya', 'Transfer Masuk', 'Adjustment Masuk', 'Riwayat Penerimaan'],
                'keyControls' => ['Monitoring sumber penerimaan kas', 'Klasifikasi penerimaan customer dan non-customer', 'Riwayat penerimaan untuk rekonsiliasi'],
            ],
            'transfer-internal' => [
                'title' => 'Transfer Internal',
                'description' => 'Memindahkan dana antar rekening perusahaan dengan tracking status transfer.',
                'modules' => ['Transfer Antar Kas/Bank', 'Daftar Transfer', 'Transfer Pending', 'Transfer Selesai', 'Riwayat Transfer'],
                'keyControls' => ['Kontrol rekening asal dan tujuan', 'Status pending vs selesai', 'Riwayat transfer untuk audit trail'],
            ],
            'petty-cash' => [
                'title' => 'Petty Cash',
                'description' => 'Pengelolaan kas kecil mulai dari permintaan dana hingga pertanggungjawaban.',
                'modules' => ['Saldo Kas Kecil', 'Permintaan Dana', 'Penggunaan Kas Kecil', 'Pengisian Kembali', 'Pertanggungjawaban', 'Riwayat Kas Kecil'],
                'keyControls' => ['Kontrol saldo kas kecil', 'Siklus penggunaan dan replenishment', 'Riwayat kas kecil untuk compliance'],
            ],
            'reconciliation' => [
                'title' => 'Reconciliation',
                'description' => 'Mencocokkan transaksi sistem dengan mutasi bank/fisik kas.',
                'modules' => ['Rekonsiliasi Bank', 'Rekonsiliasi Kas', 'Upload Mutasi Bank', 'Matching Transaksi', 'Selisih Rekonsiliasi', 'Riwayat Rekonsiliasi'],
                'keyControls' => ['Auto/manual matching', 'Analisis selisih rekonsiliasi', 'Dokumentasi hasil rekonsiliasi'],
            ],
            'journal-posting' => [
                'title' => 'Journal & Posting',
                'description' => 'Pusat posting jurnal operasional dan adjustment sampai reversal.',
                'modules' => ['Draft Posting', 'Transaksi Sudah Posting', 'Jurnal Otomatis', 'Jurnal Manual Adjustment', 'Reversal / Cancel Journal', 'Riwayat Posting'],
                'keyControls' => ['Pemantauan posting status', 'Kontrol adjustment dan reversal', 'Audit trail jurnal'],
            ],
            'reports' => [
                'title' => 'Reports',
                'description' => 'Laporan financial, operational, dan audit untuk monitoring kas menyeluruh.',
                'modules' => ['Laporan Saldo Kas & Bank', 'Laporan Arus Kas', 'Laporan Payment Request', 'Laporan Pengeluaran', 'Laporan Penerimaan', 'Laporan Transfer Internal', 'Laporan Kas Kecil', 'Outstanding Approval', 'Payment Due List', 'Vendor Payment Report', 'Cost Center Expense', 'Cash Movement', 'Audit Trail', 'Activity Log'],
                'keyControls' => ['Laporan financial dan operasional dalam satu area', 'Monitoring due payment dan outstanding approval', 'Audit reporting untuk ISO 9001'],
            ],
            'documents-audit' => [
                'title' => 'Documents & Audit',
                'description' => 'Arsip dokumen dan aktivitas user untuk memastikan ISO 9001 compliance.',
                'modules' => ['Arsip Dokumen', 'Lampiran Transaksi', 'Audit Trail', 'Log Aktivitas User', 'History Perubahan Data', 'Temuan Audit'],
                'keyControls' => ['Bukti objektif transaksi', 'Histori perubahan data', 'Manajemen temuan audit'],
            ],
            'settings' => [
                'title' => 'Settings',
                'description' => 'Konfigurasi sistem: master setting, access control, workflow, dan integrasi.',
                'modules' => ['Profil Perusahaan', 'Periode Akuntansi', 'Penomoran Dokumen', 'User', 'Role', 'Permission', 'Role Menu Access', 'Workflow Approval', 'Approval Matrix', 'Approval Level', 'Notifikasi Sistem', 'Template Export / Print', 'Integrasi Sistem', 'Backup & Restore', 'Preferensi Aplikasi'],
                'keyControls' => ['System setup dan governance', 'Role based access control', 'Workflow approval dan system config'],
            ],
        ];

        abort_unless(isset($workspaces[$module]), 404);

        return Inertia::render('Apps/CashManagement/ModulePage', $workspaces[$module]);
    }

    public function approvals(): Response
    {
        $paymentRequests = PaymentRequest::query()
            ->with(['items.partner:id,name', 'requester:id,name'])
            ->whereIn('status', ['submitted', 'under_verification', 'verified', 'waiting_approval', 'approved', 'rejected', 'revision_required'])
            ->latest('updated_at')
            ->latest('id')
            ->limit(30)
            ->get();

        return Inertia::render('Apps/CashManagement/Approvals/Index', [
            'summary' => [
                ['label' => 'Need Verification', 'value' => $paymentRequests->whereIn('status', ['submitted', 'under_verification'])->count()],
                ['label' => 'Need Approval', 'value' => $paymentRequests->whereIn('status', ['verified', 'waiting_approval'])->count()],
                ['label' => 'Approved', 'value' => $paymentRequests->where('status', 'approved')->count()],
                ['label' => 'Rejected/Revision', 'value' => $paymentRequests->whereIn('status', ['rejected', 'revision_required'])->count()],
            ],
            'approvalQueue' => $paymentRequests->map(fn (PaymentRequest $paymentRequest): array => [
                'id' => $paymentRequest->id,
                'request_no' => $paymentRequest->request_no,
                'requester' => $paymentRequest->requester?->name ?? '-',
                'vendor' => $paymentRequest->items->first()?->partner?->name ?? '-',
                'request_date' => optional($paymentRequest->request_date)->format('d M Y') ?? '-',
                'due_date' => optional($paymentRequest->due_date)->format('d M Y') ?? '-',
                'amount' => 'Rp '.number_format((float) $paymentRequest->net_amount, 0, ',', '.'),
                'status' => $paymentRequest->status,
                'status_label' => str($paymentRequest->status)->replace('_', ' ')->title()->value(),
                'rejected_reason' => $paymentRequest->rejected_reason,
            ])->values(),
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
                'id' => $paymentRequest->id,
                'request_no' => $paymentRequest->request_no,
                'vendor' => $paymentRequest->items->first()?->partner?->name ?? '-',
                'due_date' => optional($paymentRequest->due_date)->format('d M Y') ?? '-',
                'payment_method' => $paymentRequest->payment_method ?? '-',
                'source_account' => $paymentRequest->source_account ?? '-',
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

            'paymentMethodOptions' => [
                'Transfer Bank',
                'RTGS',
                'SKN',
                'Virtual Account',
                'Cash',
            ],
            'sourceAccountOptions' => [
                'Kas Besar - Head Office',
                'Bank BCA Operasional',
                'Bank Mandiri Payroll',
                'Petty Cash Cabang Surabaya',
            ],
            'recentExecutions' => $paymentRequests
                ->filter(fn (PaymentRequest $paymentRequest): bool => $mapExecutionStatus($paymentRequest) === 'paid')
                ->take(3)
                ->map(fn (PaymentRequest $paymentRequest): array => [
                    'reference' => $paymentRequest->request_no,
                    'time' => optional($paymentRequest->updated_at)->format('d M Y H:i') ?? '-',
                    'bank_channel' => $paymentRequest->payment_method ?? 'Bank channel belum diinput',
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
