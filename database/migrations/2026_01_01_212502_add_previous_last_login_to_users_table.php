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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'previous_last_login_at')) {
                $table->timestamp('previous_last_login_at')->nullable()->after('last_login_at')->comment('Previous last login timestamp (before current login)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'previous_last_login_at')) {
                $table->dropColumn('previous_last_login_at');
            }
        });
    }
};
