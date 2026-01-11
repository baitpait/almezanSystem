<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Makes operation_type and operation_eye nullable in operations table
     * to allow creating operations without default values.
     */
    public function up(): void
    {
        // For MySQL, use DB::statement to modify enum columns
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE operations MODIFY COLUMN operation_type ENUM('LASIK', 'Femto-LASIK', 'PRK', 'Trans-PRK', 'SMILE', 'PTK', 'Topography Guided', 'Presbyopia', 'Other') NULL");
            DB::statement("ALTER TABLE operations MODIFY COLUMN operation_eye ENUM('OD', 'OS', 'OU') NULL");
        } else {
            // For SQLite and other databases, use Schema
            Schema::table('operations', function (Blueprint $table) {
                $table->enum('operation_type', [
                    'LASIK',
                    'Femto-LASIK',
                    'PRK',
                    'Trans-PRK',
                    'SMILE',
                    'PTK',
                    'Topography Guided',
                    'Presbyopia',
                    'Other'
                ])->nullable()->change();
                
                $table->enum('operation_eye', ['OD', 'OS', 'OU'])->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE operations MODIFY COLUMN operation_type ENUM('LASIK', 'Femto-LASIK', 'PRK', 'Trans-PRK', 'SMILE', 'PTK', 'Topography Guided', 'Presbyopia', 'Other') NOT NULL DEFAULT 'LASIK'");
            DB::statement("ALTER TABLE operations MODIFY COLUMN operation_eye ENUM('OD', 'OS', 'OU') NOT NULL DEFAULT 'OU'");
        } else {
            Schema::table('operations', function (Blueprint $table) {
                $table->enum('operation_type', [
                    'LASIK',
                    'Femto-LASIK',
                    'PRK',
                    'Trans-PRK',
                    'SMILE',
                    'PTK',
                    'Topography Guided',
                    'Presbyopia',
                    'Other'
                ])->default('LASIK')->change();
                
                $table->enum('operation_eye', ['OD', 'OS', 'OU'])->default('OU')->change();
            });
        }
    }
};
