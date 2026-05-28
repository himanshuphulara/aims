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
        Schema::create('nivs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voc_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('issue_voc_no');
            $table->string('issue_date');
            $table->string('issue_unit');
            $table->string('issue_station');
            $table->string('receipt_voc_no');
            $table->date('receipt_date');
            $table->string('receipt_unit');
            $table->string('receipt_station');
            $table->string('issued_by');
            $table->string('received_by');
            $table->string('sig');
            $table->string('no');
            $table->date('rank');
            $table->string('name');
            $table->date('date');
            $table->json('niv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivs');
    }
};
