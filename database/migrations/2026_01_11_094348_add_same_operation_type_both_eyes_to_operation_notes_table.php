<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * 
     * Business Purpose: Add field to store user's choice of whether both eyes have the same operation type.
     * This prevents the checkbox from being auto-checked when loading existing records.
     */
    public function up(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('operation_notes', 'same_operation_type_both_eyes')) {
                $table->boolean('same_operation_type_both_eyes')->default(false)->after('operation_type_os');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            if (Schema::hasColumn('operation_notes', 'same_operation_type_both_eyes')) {
                $table->dropColumn('same_operation_type_both_eyes');
            }
        });
    }
};
