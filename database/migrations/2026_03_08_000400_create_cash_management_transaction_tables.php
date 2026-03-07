<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('rate', 8, 4)->default(0);
            $table->string('type')->default('other');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('petty_cash_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cash_account_id')->nullable()->constrained('cash_accounts')->nullOnDelete();
            $table->string('fund_code')->unique();
            $table->string('name');
            $table->decimal('current_balance', 18, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('payment_request_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason');
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_request_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('revision_no')->default(1);
            $table->foreignId('revised_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('revised_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cash_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payment_no')->unique();
            $table->date('payment_date');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('source_account_type')->nullable();
            $table->unsignedBigInteger('source_account_id')->nullable();
            $table->foreignId('partner_id')->nullable()->constrained('business_partners')->nullOnDelete();
            $table->foreignId('payment_request_id')->nullable()->constrained('payment_requests')->nullOnDelete();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('gross_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('fee_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('executed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('executed_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cash_payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->string('description');
            $table->decimal('amount', 18, 2)->default(0);
            $table->foreignId('tax_code_id')->nullable()->constrained('tax_codes')->nullOnDelete();
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_payment_id')->constrained('cash_payments')->cascadeOnDelete();
            $table->string('proof_no');
            $table->date('proof_date');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cash_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('receipt_no')->unique();
            $table->date('receipt_date');
            $table->string('target_account_type')->nullable();
            $table->unsignedBigInteger('target_account_id')->nullable();
            $table->foreignId('partner_id')->nullable()->constrained('business_partners')->nullOnDelete();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('gross_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('fee_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cash_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->string('description');
            $table->decimal('amount', 18, 2)->default(0);
            $table->foreignId('tax_code_id')->nullable()->constrained('tax_codes')->nullOnDelete();
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('net_amount', 18, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });

        Schema::create('receipt_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_receipt_id')->constrained('cash_receipts')->cascadeOnDelete();
            $table->string('proof_no');
            $table->date('proof_date');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('account_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('transfer_no')->unique();
            $table->date('transfer_date');
            $table->string('from_account_type');
            $table->unsignedBigInteger('from_account_id');
            $table->string('to_account_type');
            $table->unsignedBigInteger('to_account_id');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('fee_amount', 18, 2)->default(0);
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('executed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('transfer_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_transfer_id')->constrained('account_transfers')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->decimal('amount', 18, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained('petty_cash_funds')->cascadeOnDelete();
            $table->string('transaction_no')->unique();
            $table->date('transaction_date');
            $table->string('transaction_type');
            $table->foreignId('category_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('business_partners')->nullOnDelete();
            $table->decimal('amount', 18, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('petty_cash_replenishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained('petty_cash_funds')->cascadeOnDelete();
            $table->string('replenishment_no')->unique();
            $table->date('replenishment_date');
            $table->foreignId('source_payment_id')->nullable()->constrained('cash_payments')->nullOnDelete();
            $table->decimal('amount', 18, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('petty_cash_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_id')->constrained('petty_cash_funds')->cascadeOnDelete();
            $table->string('opname_no')->unique();
            $table->date('opname_date');
            $table->decimal('system_balance', 18, 2)->default(0);
            $table->decimal('actual_balance', 18, 2)->default(0);
            $table->decimal('difference', 18, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->date('statement_date');
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->decimal('closing_balance', 18, 2)->default(0);
            $table->string('file_name')->nullable();
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_statement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_statement_id')->constrained('bank_statements')->cascadeOnDelete();
            $table->date('transaction_date');
            $table->string('bank_reference')->nullable();
            $table->string('description')->nullable();
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('matched_status')->default('unmatched');
            $table->timestamps();
        });

        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('statement_balance', 18, 2)->default(0);
            $table->decimal('system_balance', 18, 2)->default(0);
            $table->decimal('difference', 18, 2)->default(0);
            $table->string('status')->default('open');
            $table->foreignId('prepared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliations');
        Schema::dropIfExists('bank_statement_items');
        Schema::dropIfExists('bank_statements');
        Schema::dropIfExists('petty_cash_opnames');
        Schema::dropIfExists('petty_cash_replenishments');
        Schema::dropIfExists('petty_cash_transactions');
        Schema::dropIfExists('transfer_fees');
        Schema::dropIfExists('account_transfers');
        Schema::dropIfExists('receipt_proofs');
        Schema::dropIfExists('cash_receipt_items');
        Schema::dropIfExists('cash_receipts');
        Schema::dropIfExists('payment_proofs');
        Schema::dropIfExists('cash_payment_items');
        Schema::dropIfExists('cash_payments');
        Schema::dropIfExists('payment_request_revisions');
        Schema::dropIfExists('payment_request_rejections');
        Schema::dropIfExists('petty_cash_funds');
        Schema::dropIfExists('tax_codes');
    }
};
