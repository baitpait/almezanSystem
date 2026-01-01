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
        Schema::table('operation_notes', function (Blueprint $table) {
            // General MMC fields for all operation types (OD and OS)
            if (!Schema::hasColumn('operation_notes', 'mmc_0_02_percent_od')) {
                $table->boolean('mmc_0_02_percent_od')->default(false)->after('ptk_target_os');
            }
            if (!Schema::hasColumn('operation_notes', 'mmc_duration_sec_od')) {
                $table->integer('mmc_duration_sec_od')->nullable()->after('mmc_0_02_percent_od');
            }
            if (!Schema::hasColumn('operation_notes', 'mmc_0_02_percent_os')) {
                $table->boolean('mmc_0_02_percent_os')->default(false)->after('mmc_duration_sec_od');
            }
            if (!Schema::hasColumn('operation_notes', 'mmc_duration_sec_os')) {
                $table->integer('mmc_duration_sec_os')->nullable()->after('mmc_0_02_percent_os');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            $columnsToDrop = [
                'mmc_0_02_percent_od',
                'mmc_duration_sec_od',
                'mmc_0_02_percent_os',
                'mmc_duration_sec_os',
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operation_notes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
