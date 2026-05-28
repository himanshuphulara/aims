<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restore columns expected by the legacy app (see ledgersinfo_html.sql).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->nullable()->after('password');
            }
            if (! Schema::hasColumn('users', 'profile_pic')) {
                $table->string('profile_pic', 200)->nullable()->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'unit_name')) {
                $table->string('unit_name')->nullable()->after('profile_pic');
            }
            if (! Schema::hasColumn('users', 'question')) {
                $table->string('question', 20)->nullable()->after('unit_name');
            }
        });

        // Match production dump: email is optional for username-only login.
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['role', 'profile_pic', 'unit_name', 'question'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
