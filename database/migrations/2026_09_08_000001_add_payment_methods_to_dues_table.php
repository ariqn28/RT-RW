<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->json('payment_methods')->nullable()->after('payment_info');
            $table->string('qris_image_path')->nullable()->after('payment_methods');
        });
    }

    public function down(): void
    {
        Schema::table('dues', function (Blueprint $table) {
            $table->dropColumn(['payment_methods', 'qris_image_path']);
        });
    }
};
