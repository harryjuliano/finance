<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CashManagementReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('companies')->upsert([
            ['code' => 'MJT', 'name' => 'PT Maju Jaya Teknologi', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'status', 'updated_at']);

        $companyId = DB::table('companies')->where('code', 'MJT')->value('id');

        DB::table('branches')->upsert([
            ['company_id' => $companyId, 'business_unit_id' => null, 'code' => 'BR-JKT', 'name' => 'Jakarta', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'business_unit_id' => null, 'code' => 'BR-SBY', 'name' => 'Surabaya', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['name', 'status', 'updated_at']);

        DB::table('departments')->upsert([
            ['company_id' => $companyId, 'code' => 'FIN', 'name' => 'Finance', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'code' => 'SLS', 'name' => 'Sales', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'code' => 'HRGA', 'name' => 'HRGA', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'code' => 'OPS', 'name' => 'Operations', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['name', 'status', 'updated_at']);

        $deptIds = DB::table('departments')->whereIn('code', ['FIN', 'SLS', 'HRGA', 'OPS'])->pluck('id', 'code');

        DB::table('cost_centers')->upsert([
            ['company_id' => $companyId, 'department_id' => $deptIds['FIN'], 'code' => 'FIN-HO', 'name' => 'Finance HO', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'department_id' => $deptIds['SLS'], 'code' => 'SLS-JKT', 'name' => 'Sales Jakarta', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'department_id' => $deptIds['HRGA'], 'code' => 'HRGA-HO', 'name' => 'HRGA HO', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'department_id' => $deptIds['OPS'], 'code' => 'OPS-SBY', 'name' => 'Operations Surabaya', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['department_id', 'name', 'status', 'updated_at']);

        DB::table('users')->upsert([
            ['name' => 'Rina Finance', 'email' => 'rina.finance@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['FIN'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Budi Supervisor', 'email' => 'budi.spv@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['FIN'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sari Manager', 'email' => 'sari.manager@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['FIN'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dedi Treasury', 'email' => 'dedi.treasury@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['FIN'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Andi Sales', 'email' => 'andi.sales@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['SLS'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yusuf Ops', 'email' => 'yusuf.ops@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-SBY')->value('id'), 'department_id' => $deptIds['OPS'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lilis HRGA', 'email' => 'lilis.hrga@mjt.co.id', 'password' => Hash::make('password'), 'branch_id' => DB::table('branches')->where('code', 'BR-JKT')->value('id'), 'department_id' => $deptIds['HRGA'], 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['email'], ['name', 'password', 'branch_id', 'department_id', 'status', 'updated_at']);

        DB::table('payment_methods')->upsert([
            ['code' => 'BANK_TRANSFER', 'name' => 'Transfer Bank', 'method_type' => 'bank', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CASH', 'name' => 'Cash', 'method_type' => 'cash', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'method_type', 'status', 'updated_at']);

        DB::table('tax_codes')->upsert([
            ['code' => 'PPN11', 'name' => 'PPN 11%', 'rate' => 11, 'type' => 'ppn', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PPH23', 'name' => 'PPh 23', 'rate' => 2, 'type' => 'pph', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NON-TAX', 'name' => 'Non Tax', 'rate' => 0, 'type' => 'none', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['code'], ['name', 'rate', 'type', 'status', 'updated_at']);

        $banks = DB::table('banks')->pluck('id', 'code');
        if (!$banks->has('BCA')) {
            DB::table('banks')->insert(['code' => 'BCA', 'name' => 'BCA', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]);
        }
        if (!$banks->has('MDR')) {
            DB::table('banks')->insert(['code' => 'MDR', 'name' => 'Mandiri', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]);
        }
        if (!$banks->has('BNI')) {
            DB::table('banks')->insert(['code' => 'BNI', 'name' => 'BNI', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]);
        }

        $idrId = DB::table('currencies')->where('code', 'IDR')->value('id');
        $branchJktId = DB::table('branches')->where('code', 'BR-JKT')->value('id');
        $branchSbyId = DB::table('branches')->where('code', 'BR-SBY')->value('id');

        DB::table('bank_accounts')->upsert([
            ['company_id' => $companyId, 'branch_id' => $branchJktId, 'bank_id' => DB::table('banks')->where('code', 'BCA')->value('id'), 'code' => 'BANK-HO-BCA-IDR', 'name' => 'HO BCA', 'account_number' => '123', 'account_holder_name' => 'PT MJT', 'currency_id' => $idrId, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'branch_id' => $branchJktId, 'bank_id' => DB::table('banks')->where('code', 'MDR')->value('id'), 'code' => 'BANK-HO-MDR-IDR', 'name' => 'HO Mandiri', 'account_number' => '456', 'account_holder_name' => 'PT MJT', 'currency_id' => $idrId, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'branch_id' => $branchSbyId, 'bank_id' => DB::table('banks')->where('code', 'BNI')->value('id'), 'code' => 'BANK-SBY-BNI-IDR', 'name' => 'SBY BNI', 'account_number' => '789', 'account_holder_name' => 'PT MJT', 'currency_id' => $idrId, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['bank_id', 'branch_id', 'name', 'account_number', 'account_holder_name', 'currency_id', 'status', 'updated_at']);

        DB::table('cash_accounts')->upsert([
            ['company_id' => $companyId, 'branch_id' => $branchJktId, 'code' => 'CASH-HO-001', 'name' => 'Kas Kecil HO', 'currency_id' => $idrId, 'current_balance' => 0, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['branch_id', 'name', 'currency_id', 'status', 'updated_at']);

        DB::table('petty_cash_funds')->upsert([
            ['company_id' => $companyId, 'branch_id' => $branchJktId, 'cash_account_id' => DB::table('cash_accounts')->where('code', 'CASH-HO-001')->value('id'), 'fund_code' => 'PC-HO-001', 'name' => 'Petty Cash HO', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['fund_code'], ['cash_account_id', 'name', 'status', 'updated_at']);

        $partnerGroupId = DB::table('partner_groups')->where('code', 'PG-VENDOR')->value('id');
        if (!$partnerGroupId) {
            DB::table('partner_groups')->insert(['code' => 'PG-VENDOR', 'name' => 'Vendor', 'partner_type' => 'vendor', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]);
            $partnerGroupId = DB::table('partner_groups')->where('code', 'PG-VENDOR')->value('id');
        }

        DB::table('business_partners')->upsert([
            ['company_id' => $companyId, 'partner_group_id' => $partnerGroupId, 'code' => 'VND-001', 'type' => 'vendor', 'name' => 'PT Sumber Kertas Abadi', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => $partnerGroupId, 'code' => 'VND-003', 'type' => 'vendor', 'name' => 'PT Global Media Telekom', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => $partnerGroupId, 'code' => 'VND-004', 'type' => 'vendor', 'name' => 'PT Surya Logistik Mandiri', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => $partnerGroupId, 'code' => 'VND-005', 'type' => 'vendor', 'name' => 'CV Maju Teknik', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => null, 'code' => 'CUS-001', 'type' => 'customer', 'name' => 'PT Anugerah Retailindo', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => null, 'code' => 'CUS-002', 'type' => 'customer', 'name' => 'PT Berkah Niaga Sentosa', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => null, 'code' => 'CUS-003', 'type' => 'customer', 'name' => 'PT Citra Nusantara', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'partner_group_id' => null, 'code' => 'EMP-001', 'type' => 'employee', 'name' => 'Andi Pratama', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ], ['company_id', 'code'], ['partner_group_id', 'type', 'name', 'status', 'updated_at']);

        $categoryPayload = [
            ['OPEX-ATK', 'Biaya ATK', 'outflow'],
            ['OPEX-UTIL', 'Biaya Utilitas', 'outflow'],
            ['OPEX-REIMB', 'Biaya Reimburse', 'outflow'],
            ['OPEX-MAINT', 'Biaya Maintenance', 'outflow'],
            ['OPEX-PETTY', 'Topup Petty Cash', 'outflow'],
            ['VENDOR-PAY', 'Pembayaran Vendor', 'outflow'],
            ['OPEX-TRAVEL', 'Biaya Perjalanan', 'outflow'],
            ['SALES-COLLECT', 'Penerimaan Penjualan', 'inflow'],
            ['REFUND-IN', 'Refund Masuk', 'inflow'],
            ['OTHER-INCOME', 'Pendapatan Lain', 'inflow'],
        ];

        $rows = array_map(fn (array $x) => [
            'company_id' => $companyId,
            'parent_id' => null,
            'code' => $x[0],
            'name' => $x[1],
            'flow_type' => $x[2],
            'requires_attachment' => false,
            'requires_partner' => false,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ], $categoryPayload);

        DB::table('transaction_categories')->upsert($rows, ['company_id', 'code'], ['name', 'flow_type', 'status', 'updated_at']);
    }
}
