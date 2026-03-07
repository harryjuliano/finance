<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PhaseOneController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Apps/CashManagement/PhaseOne/Index', [
            'modules' => [
                [
                    'title' => 'Organization Master',
                    'description' => 'Master perusahaan, branch, dan department.',
                    'items' => [
                        ['label' => 'Companies', 'value' => Company::query()->count()],
                        ['label' => 'Branches', 'value' => Branch::query()->count()],
                        ['label' => 'Departments', 'value' => Department::query()->count()],
                    ],
                ],
                [
                    'title' => 'Users, Roles, Permissions',
                    'description' => 'Kontrol akses pengguna berdasarkan role & permission.',
                    'items' => [
                        ['label' => 'Users', 'value' => User::query()->count()],
                        ['label' => 'Roles', 'value' => Role::query()->count()],
                        ['label' => 'Permissions', 'value' => Permission::query()->count()],
                    ],
                ],
                [
                    'title' => 'Cash / Bank Accounts',
                    'description' => 'Master akun kas dan rekening bank operasional.',
                    'items' => [
                        ['label' => 'Cash Accounts', 'value' => DB::table('cash_accounts')->count()],
                        ['label' => 'Bank Accounts', 'value' => DB::table('bank_accounts')->count()],
                    ],
                ],
                [
                    'title' => 'Transaction Categories',
                    'description' => 'Kategori transaksi untuk inflow, outflow, dan transfer.',
                    'items' => [
                        ['label' => 'Categories', 'value' => TransactionCategory::query()->count()],
                    ],
                ],
                [
                    'title' => 'Payment Request',
                    'description' => 'Pengajuan pembayaran dengan itemized detail.',
                    'items' => [
                        ['label' => 'Documents', 'value' => PaymentRequest::query()->count()],
                        ['label' => 'Submitted', 'value' => PaymentRequest::query()->where('status', 'submitted')->count()],
                    ],
                ],
                [
                    'title' => 'Cash Receipt / Cash Payment',
                    'description' => 'Pencatatan kas masuk dan kas keluar.',
                    'items' => [
                        ['label' => 'Cash Receipt', 'value' => Transaction::query()->where('type', 'Cash In')->count()],
                        ['label' => 'Cash Payment', 'value' => Transaction::query()->whereIn('type', ['Cash Out', 'Payment Request'])->count()],
                    ],
                ],
                [
                    'title' => 'Approval Basic',
                    'description' => 'Tracking status approval dokumen secara dasar.',
                    'items' => [
                        ['label' => 'Workflows', 'value' => DB::table('approval_workflows')->count()],
                        ['label' => 'Waiting Approval', 'value' => DB::table('approval_workflows')->where('status', 'waiting_approval')->count()],
                    ],
                ],
                [
                    'title' => 'Attachment',
                    'description' => 'Data lampiran dokumen pendukung transaksi.',
                    'items' => [
                        ['label' => 'Attachments', 'value' => DB::table('attachments')->count()],
                    ],
                ],
                [
                    'title' => 'Audit Log Basic',
                    'description' => 'Catatan aktivitas user dan perubahan dokumen.',
                    'items' => [
                        ['label' => 'Activity Logs', 'value' => DB::table('activity_logs')->count()],
                    ],
                ],
            ],
            'recentPaymentRequests' => PaymentRequest::query()
                ->select('id', 'request_no', 'request_date', 'status', 'net_amount')
                ->latest('id')
                ->take(5)
                ->get(),
            'recentTransactions' => Transaction::query()
                ->select('id', 'reference_no', 'transaction_date', 'type', 'amount', 'status')
                ->latest('id')
                ->take(5)
                ->get(),
            'recentActivities' => DB::table('activity_logs')
                ->select('id', 'module', 'document_type', 'action', 'created_at')
                ->latest('id')
                ->take(5)
                ->get(),
        ]);
    }
}
