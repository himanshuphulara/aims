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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voc_fund_type')->nullable();
            $table->string('voc_type')->nullable();
            $table->string('voc_date')->nullable();
            $table->string('voc_no')->nullable();
            $table->string('voc_file')->nullable();
            $table->string('voc_whom')->nullable();
            $table->string('voc_acc')->nullable();
            $table->string('voc_cash')->nullable();
            $table->string('voc_bank')->nullable();
            $table->json('voc_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
