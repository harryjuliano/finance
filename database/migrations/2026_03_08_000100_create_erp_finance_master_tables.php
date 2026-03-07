<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('symbol', 8)->nullable();
            $table->boolean('is_base_currency')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('swift_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('custodian_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->decimal('current_balance', 18, 2)->default(0);
            $table->decimal('minimum_balance', 18, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->decimal('current_balance', 18, 2)->default(0);
            $table->decimal('minimum_balance', 18, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('transaction_categories')->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->enum('flow_type', ['inflow', 'outflow', 'transfer']);
            $table->unsignedBigInteger('default_gl_account_id')->nullable();
            $table->boolean('requires_attachment')->default(false);
            $table->boolean('requires_partner')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('method_type');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('partner_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('partner_type');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('business_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('type');
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->unique(['company_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_partners');
        Schema::dropIfExists('partner_groups');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('transaction_categories');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('cash_accounts');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('currencies');
    }
};
