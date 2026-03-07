<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErpFinanceMasterSeeder extends Seeder
{
    /**
     * Seed ERP finance master data.
     */
    public function run(): void
    {
        $now = now();

        DB::table('companies')->upsert([
            [
                'code' => 'COMP-HO',
                'name' => 'PT Finance Demo',
                'legal_name' => 'PT Finance Demo Indonesia',
                'tax_id' => '01.234.567.8-901.000',
                'address' => 'Jakarta, Indonesia',
                'phone' => '+62-21-12345678',
                'email' => 'finance@demo.id',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['name', 'legal_name', 'tax_id', 'address', 'phone', 'email', 'status', 'updated_at']);

        $companyId = DB::table('companies')->where('code', 'COMP-HO')->value('id');

        DB::table('business_units')->upsert([
            [
                'company_id' => $companyId,
                'code' => 'BU-CORP',
                'name' => 'Corporate',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['name', 'status', 'updated_at']);

        $businessUnitId = DB::table('business_units')
            ->where('company_id', $companyId)
            ->where('code', 'BU-CORP')
            ->value('id');

        DB::table('branches')->upsert([
            [
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
                'code' => 'BR-HO',
                'name' => 'Head Office',
                'address' => 'Jakarta Pusat',
                'phone' => '+62-21-12345678',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'company_id' => $companyId,
                'business_unit_id' => $businessUnitId,
                'code' => 'BR-SBY',
                'name' => 'Surabaya Branch',
                'address' => 'Surabaya',
                'phone' => '+62-31-9876543',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['business_unit_id', 'name', 'address', 'phone', 'status', 'updated_at']);

        DB::table('departments')->upsert([
            [
                'company_id' => $companyId,
                'code' => 'DEP-FIN',
                'name' => 'Finance',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'company_id' => $companyId,
                'code' => 'DEP-OPS',
                'name' => 'Operations',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['name', 'status', 'updated_at']);

        $financeDepartmentId = DB::table('departments')
            ->where('company_id', $companyId)
            ->where('code', 'DEP-FIN')
            ->value('id');

        DB::table('cost_centers')->upsert([
            [
                'company_id' => $companyId,
                'department_id' => $financeDepartmentId,
                'code' => 'CC-FIN-HO',
                'name' => 'Finance HO',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'company_id' => $companyId,
                'department_id' => $financeDepartmentId,
                'code' => 'CC-OPS-HO',
                'name' => 'Operations HO',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['department_id', 'name', 'status', 'updated_at']);

        DB::table('projects')->upsert([
            [
                'company_id' => $companyId,
                'code' => 'PRJ-DIGI-2026',
                'name' => 'Digitalisasi Finance 2026',
                'start_date' => now()->subMonths(1)->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['name', 'start_date', 'end_date', 'status', 'updated_at']);

        DB::table('currencies')->upsert([
            [
                'code' => 'IDR',
                'name' => 'Rupiah',
                'symbol' => 'Rp',
                'is_base_currency' => true,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'is_base_currency' => false,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['name', 'symbol', 'is_base_currency', 'status', 'updated_at']);

        DB::table('banks')->upsert([
            [
                'code' => 'BCA',
                'name' => 'Bank Central Asia',
                'swift_code' => 'CENAIDJA',
                'branch_name' => 'Jakarta',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['name', 'swift_code', 'branch_name', 'status', 'updated_at']);

        DB::table('payment_methods')->upsert([
            [
                'code' => 'BANK_TRANSFER',
                'name' => 'Transfer Bank',
                'method_type' => 'bank',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'PETTY_CASH',
                'name' => 'Petty Cash',
                'method_type' => 'cash',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['name', 'method_type', 'status', 'updated_at']);

        DB::table('partner_groups')->upsert([
            [
                'code' => 'PG-VENDOR',
                'name' => 'Vendor',
                'partner_type' => 'vendor',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['name', 'partner_type', 'status', 'updated_at']);

        $partnerGroupId = DB::table('partner_groups')->where('code', 'PG-VENDOR')->value('id');

        DB::table('transaction_categories')->upsert([
            [
                'company_id' => $companyId,
                'parent_id' => null,
                'code' => 'TRX-PR-OPS',
                'name' => 'Payment Request Operasional',
                'flow_type' => 'outflow',
                'default_gl_account_id' => null,
                'requires_attachment' => true,
                'requires_partner' => true,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['parent_id', 'name', 'flow_type', 'default_gl_account_id', 'requires_attachment', 'requires_partner', 'status', 'updated_at']);

        DB::table('business_partners')->upsert([
            [
                'company_id' => $companyId,
                'partner_group_id' => $partnerGroupId,
                'code' => 'VENDOR-PTMJU',
                'type' => 'vendor',
                'name' => 'PT Maju Jaya Utama',
                'legal_name' => 'PT Maju Jaya Utama',
                'tax_number' => '09.876.543.2-100.000',
                'address' => 'Surabaya, Indonesia',
                'phone' => '+62-31-11223344',
                'email' => 'ap@ptmju.co.id',
                'contact_person' => 'Budi Santoso',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['company_id', 'code'], ['partner_group_id', 'type', 'name', 'legal_name', 'tax_number', 'address', 'phone', 'email', 'contact_person', 'status', 'updated_at']);
    }
}
