<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashManagementTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            $companyId = DB::table('companies')->where('code', 'MJT')->value('id');

            $branchJakartaId = DB::table('branches')->where('code', 'BR-JKT')->value('id');
            $branchSurabayaId = DB::table('branches')->where('code', 'BR-SBY')->value('id');

            $deptFinanceId = DB::table('departments')->where('code', 'FIN')->value('id');
            $deptSalesId = DB::table('departments')->where('code', 'SLS')->value('id');
            $deptHrgaId = DB::table('departments')->where('code', 'HRGA')->value('id');
            $deptOpsId = DB::table('departments')->where('code', 'OPS')->value('id');

            $ccFinHoId = DB::table('cost_centers')->where('code', 'FIN-HO')->value('id');
            $ccSalesJktId = DB::table('cost_centers')->where('code', 'SLS-JKT')->value('id');
            $ccHrgaHoId = DB::table('cost_centers')->where('code', 'HRGA-HO')->value('id');
            $ccOpsSbyId = DB::table('cost_centers')->where('code', 'OPS-SBY')->value('id');

            $rinaId = DB::table('users')->where('email', 'rina.finance@mjt.co.id')->value('id');
            $budiId = DB::table('users')->where('email', 'budi.spv@mjt.co.id')->value('id');
            $sariId = DB::table('users')->where('email', 'sari.manager@mjt.co.id')->value('id');
            $dediId = DB::table('users')->where('email', 'dedi.treasury@mjt.co.id')->value('id');
            $andiUserId = DB::table('users')->where('email', 'andi.sales@mjt.co.id')->value('id');
            $yusufUserId = DB::table('users')->where('email', 'yusuf.ops@mjt.co.id')->value('id');
            $lilisUserId = DB::table('users')->where('email', 'lilis.hrga@mjt.co.id')->value('id');

            $idrId = DB::table('currencies')->where('code', 'IDR')->value('id');

            $bankHoBcaId = DB::table('bank_accounts')->where('code', 'BANK-HO-BCA-IDR')->value('id');
            $bankHoMandiriId = DB::table('bank_accounts')->where('code', 'BANK-HO-MDR-IDR')->value('id');
            $bankSbyBniId = DB::table('bank_accounts')->where('code', 'BANK-SBY-BNI-IDR')->value('id');

            $pcFundId = DB::table('petty_cash_funds')->where('fund_code', 'PC-HO-001')->value('id');

            $catAtkId = DB::table('transaction_categories')->where('code', 'OPEX-ATK')->value('id');
            $catUtilId = DB::table('transaction_categories')->where('code', 'OPEX-UTIL')->value('id');
            $catReimbId = DB::table('transaction_categories')->where('code', 'OPEX-REIMB')->value('id');
            $catMaintId = DB::table('transaction_categories')->where('code', 'OPEX-MAINT')->value('id');
            $catPettyId = DB::table('transaction_categories')->where('code', 'OPEX-PETTY')->value('id');
            $catVendorPayId = DB::table('transaction_categories')->where('code', 'VENDOR-PAY')->value('id');
            $catTravelId = DB::table('transaction_categories')->where('code', 'OPEX-TRAVEL')->value('id');
            $catSalesCollectId = DB::table('transaction_categories')->where('code', 'SALES-COLLECT')->value('id');
            $catRefundInId = DB::table('transaction_categories')->where('code', 'REFUND-IN')->value('id');
            $catOtherIncomeId = DB::table('transaction_categories')->where('code', 'OTHER-INCOME')->value('id');

            $taxPpnId = DB::table('tax_codes')->where('code', 'PPN11')->value('id');
            $taxPph23Id = DB::table('tax_codes')->where('code', 'PPH23')->value('id');
            $taxNoneId = DB::table('tax_codes')->where('code', 'NON-TAX')->value('id');

            $methodBankTransferId = DB::table('payment_methods')->where('code', 'BANK_TRANSFER')->value('id');
            $methodCashId = DB::table('payment_methods')->where('code', 'CASH')->value('id');

            $vendorSupplierId = DB::table('business_partners')->where('code', 'VND-001')->value('id');
            $vendorInternetId = DB::table('business_partners')->where('code', 'VND-003')->value('id');
            $vendorLogistikId = DB::table('business_partners')->where('code', 'VND-004')->value('id');
            $vendorTeknikId = DB::table('business_partners')->where('code', 'VND-005')->value('id');
            $customer1Id = DB::table('business_partners')->where('code', 'CUS-001')->value('id');
            $customer2Id = DB::table('business_partners')->where('code', 'CUS-002')->value('id');
            $customer3Id = DB::table('business_partners')->where('code', 'CUS-003')->value('id');
            $employeeAndiPartnerId = DB::table('business_partners')->where('code', 'EMP-001')->value('id');

            $pr1Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'department_id' => $deptHrgaId,
                'cost_center_id' => $ccHrgaHoId,
                'project_id' => null,
                'requester_id' => $lilisUserId,
                'request_no' => 'PR/FIN/2026/03/0001',
                'request_date' => '2026-03-02',
                'priority' => 'normal',
                'due_date' => '2026-03-05',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 3250000,
                'tax_amount' => 357500,
                'net_amount' => 3607500,
                'description' => 'Pembelian ATK bulanan HO',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-02 09:00:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-02 11:00:00',
                'approved_at' => '2026-03-02 13:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr1Id,
                'category_id' => $catAtkId,
                'partner_id' => $vendorSupplierId,
                'description' => 'Pembelian ATK bulanan HO',
                'qty' => 1,
                'unit_price' => 3250000,
                'amount' => 3250000,
                'tax_code_id' => $taxPpnId,
                'tax_amount' => 357500,
                'net_amount' => 3607500,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr2Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'department_id' => $deptFinanceId,
                'cost_center_id' => $ccFinHoId,
                'project_id' => null,
                'requester_id' => $rinaId,
                'request_no' => 'PR/FIN/2026/03/0002',
                'request_date' => '2026-03-03',
                'priority' => 'normal',
                'due_date' => '2026-03-07',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 8500000,
                'tax_amount' => 935000,
                'net_amount' => 9435000,
                'description' => 'Tagihan internet kantor Maret 2026',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-03 09:10:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-03 11:25:00',
                'approved_at' => '2026-03-03 14:40:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr2Id,
                'category_id' => $catUtilId,
                'partner_id' => $vendorInternetId,
                'description' => 'Tagihan internet kantor Maret 2026',
                'qty' => 1,
                'unit_price' => 8500000,
                'amount' => 8500000,
                'tax_code_id' => $taxPpnId,
                'tax_amount' => 935000,
                'net_amount' => 9435000,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr3Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'department_id' => $deptSalesId,
                'cost_center_id' => $ccSalesJktId,
                'project_id' => null,
                'requester_id' => $andiUserId,
                'request_no' => 'PR/FIN/2026/03/0003',
                'request_date' => '2026-03-04',
                'priority' => 'normal',
                'due_date' => '2026-03-06',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 2150000,
                'tax_amount' => 0,
                'net_amount' => 2150000,
                'description' => 'Reimbursement hotel & transport kunjungan customer',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-04 08:30:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-04 10:00:00',
                'approved_at' => '2026-03-04 11:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr3Id,
                'category_id' => $catReimbId,
                'partner_id' => $employeeAndiPartnerId,
                'description' => 'Reimbursement hotel & transport kunjungan customer',
                'qty' => 1,
                'unit_price' => 2150000,
                'amount' => 2150000,
                'tax_code_id' => $taxNoneId,
                'tax_amount' => 0,
                'net_amount' => 2150000,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr4Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'department_id' => $deptOpsId,
                'cost_center_id' => $ccFinHoId,
                'project_id' => null,
                'requester_id' => $yusufUserId,
                'request_no' => 'PR/FIN/2026/03/0004',
                'request_date' => '2026-03-05',
                'priority' => 'normal',
                'due_date' => '2026-03-10',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 12000000,
                'tax_amount' => 240000,
                'net_amount' => 11760000,
                'description' => 'Service AC area kantor HO',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-05 09:15:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-05 11:20:00',
                'approved_at' => '2026-03-05 13:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr4Id,
                'category_id' => $catMaintId,
                'partner_id' => $vendorTeknikId,
                'description' => 'Service AC area kantor HO',
                'qty' => 1,
                'unit_price' => 12000000,
                'amount' => 12000000,
                'tax_code_id' => $taxPph23Id,
                'tax_amount' => 240000,
                'net_amount' => 11760000,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr5Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'department_id' => $deptFinanceId,
                'cost_center_id' => $ccFinHoId,
                'project_id' => null,
                'requester_id' => $rinaId,
                'request_no' => 'PR/FIN/2026/03/0005',
                'request_date' => '2026-03-06',
                'priority' => 'high',
                'due_date' => '2026-03-06',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 5000000,
                'tax_amount' => 0,
                'net_amount' => 5000000,
                'description' => 'Pengisian kas kecil HO',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-06 08:00:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-06 08:30:00',
                'approved_at' => '2026-03-06 09:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr5Id,
                'category_id' => $catPettyId,
                'partner_id' => null,
                'description' => 'Pengisian kas kecil HO',
                'qty' => 1,
                'unit_price' => 5000000,
                'amount' => 5000000,
                'tax_code_id' => $taxNoneId,
                'tax_amount' => 0,
                'net_amount' => 5000000,
                'reference_type' => 'petty_cash_fund',
                'reference_id' => $pcFundId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr6Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchSurabayaId,
                'department_id' => $deptOpsId,
                'cost_center_id' => $ccOpsSbyId,
                'project_id' => null,
                'requester_id' => $yusufUserId,
                'request_no' => 'PR/FIN/2026/03/0006',
                'request_date' => '2026-03-07',
                'priority' => 'normal',
                'due_date' => '2026-03-12',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 6800000,
                'tax_amount' => 748000,
                'net_amount' => 7548000,
                'description' => 'Rak arsip dan perlengkapan gudang',
                'status' => 'rejected',
                'verification_status' => 'rejected',
                'approval_status' => 'rejected',
                'payment_status' => 'unpaid',
                'document_complete_flag' => 0,
                'submitted_at' => '2026-03-07 10:00:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-07 13:20:00',
                'approved_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr6Id,
                'category_id' => $catAtkId,
                'partner_id' => $vendorSupplierId,
                'description' => 'Rak arsip dan perlengkapan gudang',
                'qty' => 1,
                'unit_price' => 6800000,
                'amount' => 6800000,
                'tax_code_id' => $taxPpnId,
                'tax_amount' => 748000,
                'net_amount' => 7548000,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_rejections')->insert([
                'payment_request_id' => $pr6Id,
                'rejected_by' => $budiId,
                'reason' => 'Lampiran invoice belum ditandatangani vendor',
                'rejected_at' => '2026-03-07 13:25:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr7Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchSurabayaId,
                'department_id' => $deptOpsId,
                'cost_center_id' => $ccOpsSbyId,
                'project_id' => null,
                'requester_id' => $yusufUserId,
                'request_no' => 'PR/FIN/2026/03/0007',
                'request_date' => '2026-03-08',
                'priority' => 'normal',
                'due_date' => '2026-03-12',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 6800000,
                'tax_amount' => 748000,
                'net_amount' => 7548000,
                'description' => 'Rak arsip dan perlengkapan gudang - revisi',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-08 09:00:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-08 11:00:00',
                'approved_at' => '2026-03-08 14:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr7Id,
                'category_id' => $catAtkId,
                'partner_id' => $vendorSupplierId,
                'description' => 'Rak arsip dan perlengkapan gudang - revisi',
                'qty' => 1,
                'unit_price' => 6800000,
                'amount' => 6800000,
                'tax_code_id' => $taxPpnId,
                'tax_amount' => 748000,
                'net_amount' => 7548000,
                'reference_type' => 'payment_request',
                'reference_id' => $pr6Id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_revisions')->insert([
                'payment_request_id' => $pr7Id,
                'revision_no' => 1,
                'revised_by' => $yusufUserId,
                'notes' => 'Revisi dari PR/FIN/2026/03/0006',
                'revised_at' => '2026-03-08 08:45:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pr8Id = DB::table('payment_requests')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchSurabayaId,
                'department_id' => $deptOpsId,
                'cost_center_id' => $ccOpsSbyId,
                'project_id' => null,
                'requester_id' => $yusufUserId,
                'request_no' => 'PR/FIN/2026/03/0008',
                'request_date' => '2026-03-10',
                'priority' => 'normal',
                'due_date' => '2026-03-15',
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'total_amount' => 18500000,
                'tax_amount' => 370000,
                'net_amount' => 18130000,
                'description' => 'Jasa pengiriman barang area Jawa Timur',
                'status' => 'approved',
                'verification_status' => 'verified',
                'approval_status' => 'approved',
                'payment_status' => 'paid',
                'document_complete_flag' => 1,
                'submitted_at' => '2026-03-10 09:00:00',
                'verified_by' => $budiId,
                'verified_at' => '2026-03-10 10:30:00',
                'approved_at' => '2026-03-10 14:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('payment_request_items')->insert([
                'payment_request_id' => $pr8Id,
                'category_id' => $catVendorPayId,
                'partner_id' => $vendorLogistikId,
                'description' => 'Jasa pengiriman barang area Jawa Timur',
                'qty' => 1,
                'unit_price' => 18500000,
                'amount' => 18500000,
                'tax_code_id' => $taxPph23Id,
                'tax_amount' => 370000,
                'net_amount' => 18130000,
                'reference_type' => null,
                'reference_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->seedApprovalWorkflow($pr1Id, $budiId, null, '2026-03-02 09:00:00', '2026-03-02 13:00:00', $now);
            $this->seedApprovalWorkflow($pr2Id, $budiId, $sariId, '2026-03-03 09:10:00', '2026-03-03 14:40:00', $now);
            $this->seedApprovalWorkflow($pr3Id, $budiId, null, '2026-03-04 08:30:00', '2026-03-04 11:00:00', $now);
            $this->seedApprovalWorkflow($pr4Id, $budiId, $sariId, '2026-03-05 09:15:00', '2026-03-05 13:00:00', $now);
            $this->seedApprovalWorkflow($pr5Id, $budiId, null, '2026-03-06 08:00:00', '2026-03-06 09:00:00', $now);
            $this->seedApprovalWorkflow($pr7Id, $budiId, $sariId, '2026-03-08 09:00:00', '2026-03-08 14:00:00', $now);
            $this->seedApprovalWorkflow($pr8Id, $budiId, $sariId, '2026-03-10 09:00:00', '2026-03-10 14:00:00', $now);

            $pay1Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'payment_no' => 'CPV/FIN/2026/03/0001',
                'payment_date' => '2026-03-05',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankHoBcaId,
                'partner_id' => $vendorSupplierId,
                'payment_request_id' => $pr1Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 3250000,
                'tax_amount' => 357500,
                'fee_amount' => 6500,
                'net_amount' => 3607500,
                'reference_no' => 'PR/FIN/2026/03/0001',
                'description' => 'Pembayaran ATK bulanan HO',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-05 10:00:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-05 10:05:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay2Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'payment_no' => 'CPV/FIN/2026/03/0002',
                'payment_date' => '2026-03-07',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankHoBcaId,
                'partner_id' => $vendorInternetId,
                'payment_request_id' => $pr2Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 8500000,
                'tax_amount' => 935000,
                'fee_amount' => 6500,
                'net_amount' => 9435000,
                'reference_no' => 'PR/FIN/2026/03/0002',
                'description' => 'Pembayaran internet Maret 2026',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-07 08:45:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-07 08:50:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay3Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'payment_no' => 'CPV/FIN/2026/03/0003',
                'payment_date' => '2026-03-06',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankHoBcaId,
                'partner_id' => $employeeAndiPartnerId,
                'payment_request_id' => $pr3Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 2150000,
                'tax_amount' => 0,
                'fee_amount' => 2500,
                'net_amount' => 2150000,
                'reference_no' => 'PR/FIN/2026/03/0003',
                'description' => 'Reimbursement Andi Pratama',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-06 11:00:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-06 11:05:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay4Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'payment_no' => 'CPV/FIN/2026/03/0004',
                'payment_date' => '2026-03-10',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankHoBcaId,
                'partner_id' => $vendorTeknikId,
                'payment_request_id' => $pr4Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 12000000,
                'tax_amount' => 240000,
                'fee_amount' => 6500,
                'net_amount' => 11760000,
                'reference_no' => 'PR/FIN/2026/03/0004',
                'description' => 'Pembayaran service AC area kantor HO',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-10 10:00:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-10 10:10:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay5Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchJakartaId,
                'payment_no' => 'CPV/FIN/2026/03/0005',
                'payment_date' => '2026-03-06',
                'payment_method_id' => $methodCashId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankHoBcaId,
                'partner_id' => null,
                'payment_request_id' => $pr5Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 5000000,
                'tax_amount' => 0,
                'fee_amount' => 0,
                'net_amount' => 5000000,
                'reference_no' => 'PR/FIN/2026/03/0005',
                'description' => 'Pengisian kas kecil HO',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-06 09:30:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-06 09:35:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay6Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchSurabayaId,
                'payment_no' => 'CPV/FIN/2026/03/0006',
                'payment_date' => '2026-03-12',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankSbyBniId,
                'partner_id' => $vendorSupplierId,
                'payment_request_id' => $pr7Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 6800000,
                'tax_amount' => 748000,
                'fee_amount' => 6500,
                'net_amount' => 7548000,
                'reference_no' => 'PR/FIN/2026/03/0007',
                'description' => 'Pembayaran rak arsip gudang Surabaya',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-12 09:00:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-12 09:10:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $pay7Id = DB::table('cash_payments')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchSurabayaId,
                'payment_no' => 'CPV/FIN/2026/03/0007',
                'payment_date' => '2026-03-15',
                'payment_method_id' => $methodBankTransferId,
                'source_account_type' => 'bank_account',
                'source_account_id' => $bankSbyBniId,
                'partner_id' => $vendorLogistikId,
                'payment_request_id' => $pr8Id,
                'currency_id' => $idrId,
                'exchange_rate' => 1,
                'gross_amount' => 18500000,
                'tax_amount' => 370000,
                'fee_amount' => 6500,
                'net_amount' => 18130000,
                'reference_no' => 'PR/FIN/2026/03/0008',
                'description' => 'Pembayaran vendor logistik area Jawa Timur',
                'status' => 'posted',
                'executed_by' => $dediId,
                'executed_at' => '2026-03-15 10:00:00',
                'posted_by' => $rinaId,
                'posted_at' => '2026-03-15 10:05:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ([
                [$pay1Id, $catAtkId, 3250000, $taxPpnId, 357500, 3607500, 'Pembayaran ATK bulanan HO'],
                [$pay2Id, $catUtilId, 8500000, $taxPpnId, 935000, 9435000, 'Pembayaran internet Maret 2026'],
                [$pay3Id, $catReimbId, 2150000, $taxNoneId, 0, 2150000, 'Reimbursement Andi Pratama'],
                [$pay4Id, $catMaintId, 12000000, $taxPph23Id, 240000, 11760000, 'Pembayaran service AC area kantor HO'],
                [$pay5Id, $catPettyId, 5000000, $taxNoneId, 0, 5000000, 'Pengisian kas kecil HO'],
                [$pay6Id, $catAtkId, 6800000, $taxPpnId, 748000, 7548000, 'Pembayaran rak arsip gudang Surabaya'],
                [$pay7Id, $catVendorPayId, 18500000, $taxPph23Id, 370000, 18130000, 'Pembayaran vendor logistik area Jawa Timur'],
            ] as [$cashPaymentId, $categoryId, $amount, $taxCodeId, $taxAmount, $netAmount, $description]) {
                DB::table('cash_payment_items')->insert([
                    'cash_payment_id' => $cashPaymentId,
                    'category_id' => $categoryId,
                    'description' => $description,
                    'amount' => $amount,
                    'tax_code_id' => $taxCodeId,
                    'tax_amount' => $taxAmount,
                    'net_amount' => $netAmount,
                    'reference_type' => null,
                    'reference_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('payment_proofs')->insert([
                ['cash_payment_id' => $pay1Id, 'proof_no' => 'TRX-BCA-05032026-001', 'proof_date' => '2026-03-05', 'file_path' => 'proofs/transfer_bca_05032026_001.pdf', 'uploaded_by' => $dediId, 'created_at' => $now, 'updated_at' => $now],
                ['cash_payment_id' => $pay2Id, 'proof_no' => 'TRX-BCA-07032026-002', 'proof_date' => '2026-03-07', 'file_path' => 'proofs/transfer_bca_07032026_002.pdf', 'uploaded_by' => $dediId, 'created_at' => $now, 'updated_at' => $now],
            ]);

            DB::table('cash_accounts')->where('code', 'CASH-HO-001')->update(['current_balance' => 13500000, 'updated_at' => $now]);
            DB::table('petty_cash_funds')->where('id', $pcFundId)->update(['current_balance' => 13500000, 'updated_at' => $now]);
        });
    }

    private function seedApprovalWorkflow(
        int $paymentRequestId,
        int $level1ApproverId,
        ?int $level2ApproverId,
        string $submittedAt,
        string $approvedAt,
        Carbon $now
    ): void {
        $workflowId = DB::table('approval_workflows')->insertGetId([
            'document_type' => 'payment_request',
            'document_id' => $paymentRequestId,
            'current_level' => $level2ApproverId ? 2 : 1,
            'status' => 'approved',
            'submitted_by' => null,
            'submitted_at' => $submittedAt,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('approval_steps')->insert([
            'workflow_id' => $workflowId,
            'level_no' => 1,
            'approver_user_id' => $level1ApproverId,
            'approver_role_id' => null,
            'action' => 'approved',
            'notes' => 'Approved level 1',
            'acted_at' => Carbon::parse($submittedAt)->addHours(2),
            'status' => 'approved',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($level2ApproverId) {
            DB::table('approval_steps')->insert([
                'workflow_id' => $workflowId,
                'level_no' => 2,
                'approver_user_id' => $level2ApproverId,
                'approver_role_id' => null,
                'action' => 'approved',
                'notes' => 'Approved level 2',
                'acted_at' => $approvedAt,
                'status' => 'approved',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
