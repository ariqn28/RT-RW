<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('due_id')->constrained('dues')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->string('status')->default('menunggu');
            $table->timestamps();
            $table->unique(['due_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
