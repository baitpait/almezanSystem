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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('id');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete()->after('created_by');
            
            $table->index('created_by');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['branch_id']);
            $table->dropColumn(['created_by', 'branch_id']);
        });
    }
};
