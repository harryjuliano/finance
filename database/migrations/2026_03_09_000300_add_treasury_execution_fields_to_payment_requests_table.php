<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('source_account')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'source_account', 'paid_at']);
        });
    }
};

