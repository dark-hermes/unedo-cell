<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reparation_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reparation_id')->constrained()->onDelete('cascade');
            $table->string('transaction_code')->unique();
            $table->double('amount');
            $table->string('payment_method')->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparation_transactions');
    }
};
