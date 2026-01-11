<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add visit_stage column if it doesn't exist
            if (!Schema::hasColumn('appointments', 'visit_stage')) {
                $table->enum('visit_stage', ['waiting', 'in_consultation', 'completed'])->nullable()->after('status');
            }

            // Add visit_type column if it doesn't exist
            if (!Schema::hasColumn('appointments', 'visit_type')) {
                $table->enum('visit_type', ['Assessment', 'Operation', 'Follow up', 'New visit'])->nullable()->after('visit_stage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['visit_stage', 'visit_type']);
        });
    }
};
