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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('voc_fd', 10, 2)->default(0)->after('voc_property');
        });

        Schema::table('vouchers_bbf', function (Blueprint $table) {
            $table->decimal('voc_fd', 10, 2)->default(0)->after('voc_property');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('voc_fd');
        });

        Schema::table('vouchers_bbf', function (Blueprint $table) {
            $table->dropColumn('voc_fd');
        });
    }
};
