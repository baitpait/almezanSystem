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
        Schema::table('operation_notes', function (Blueprint $table) {
            // Add separate operation_type fields for each eye
            if (!Schema::hasColumn('operation_notes', 'operation_type_od')) {
                $table->string('operation_type_od')->nullable()->after('operation_type');
            }
            if (!Schema::hasColumn('operation_notes', 'operation_type_os')) {
                $table->string('operation_type_os')->nullable()->after('operation_type_od');
            }
        });

        // Change monovision from boolean to enum
        if (Schema::hasColumn('operation_notes', 'monovision')) {
            // First, convert existing boolean values to enum
            DB::statement("UPDATE `operation_notes` SET `monovision` = NULL WHERE `monovision` = 0 OR `monovision` IS NULL");
            DB::statement("UPDATE `operation_notes` SET `monovision` = 'OD' WHERE `monovision` = 1");
            
            // Drop the boolean column
            Schema::table('operation_notes', function (Blueprint $table) {
                $table->dropColumn('monovision');
            });
            
            // Add enum column
            Schema::table('operation_notes', function (Blueprint $table) {
                $table->enum('monovision_eye', ['none', 'OD', 'OS'])->nullable()->after('operation_eye');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            if (Schema::hasColumn('operation_notes', 'operation_type_od')) {
                $table->dropColumn('operation_type_od');
            }
            if (Schema::hasColumn('operation_notes', 'operation_type_os')) {
                $table->dropColumn('operation_type_os');
            }
        });

        // Revert monovision_eye to boolean monovision
        if (Schema::hasColumn('operation_notes', 'monovision_eye')) {
            // Convert enum values back to boolean
            DB::statement("UPDATE `operation_notes` SET `monovision_eye` = NULL WHERE `monovision_eye` = 'none' OR `monovision_eye` IS NULL");
            
            Schema::table('operation_notes', function (Blueprint $table) {
                $table->dropColumn('monovision_eye');
            });
            
            Schema::table('operation_notes', function (Blueprint $table) {
                $table->boolean('monovision')->default(false)->after('operation_eye');
            });
        }
    }
};
