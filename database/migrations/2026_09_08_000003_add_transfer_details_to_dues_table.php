<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('qris_image_path');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_holder')->nullable()->after('account_number');
            $table->string('bifast_number')->nullable()->after('account_holder');
            $table->string('cash_payment_info')->nullable()->after('bifast_number');
        });
    }

    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'account_holder', 'bifast_number', 'cash_payment_info']);
        });
    }
};
