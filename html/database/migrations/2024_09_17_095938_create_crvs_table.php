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
        Schema::create('crvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voc_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('issue_voc_no');
            $table->string('issue_expense');
            $table->string('issue_unit');
            $table->string('issue_station');
            $table->string('receipt_voc_no');
            $table->date('receipt_date');
            $table->string('receipt_unit');
            $table->string('receipt_station');
            $table->string('purchase_from');
            $table->string('for_fy');
            $table->string('bill_no');
            $table->string('gem');
            $table->date('dt');
            $table->string('contact_no');
            $table->string('gemcrac');
            $table->date('dated');
            $table->json('crv')->nullable();
            $table->text('holder_sign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crvs');
    }
};
