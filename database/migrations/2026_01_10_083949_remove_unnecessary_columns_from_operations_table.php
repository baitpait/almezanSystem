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
     * Removes unnecessary columns from operations table:
     * - operation_type
     * - operation_type_od
     * - operation_type_os
     * - operation_eye
     * - cost
     */
    public function up(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Drop indexes first if they exist
            if (Schema::hasColumn('operations', 'operation_type')) {
                $table->dropIndex(['operation_type']);
            }
            
            // Drop columns
            if (Schema::hasColumn('operations', 'operation_type')) {
                $table->dropColumn('operation_type');
            }
            if (Schema::hasColumn('operations', 'operation_type_od')) {
                $table->dropColumn('operation_type_od');
            }
            if (Schema::hasColumn('operations', 'operation_type_os')) {
                $table->dropColumn('operation_type_os');
            }
            if (Schema::hasColumn('operations', 'operation_eye')) {
                $table->dropColumn('operation_eye');
            }
            if (Schema::hasColumn('operations', 'cost')) {
                $table->dropColumn('cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Restore columns with their original definitions
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
            ])->nullable()->after('appointment_id');
            
            $table->string('operation_type_od')->nullable()->after('operation_type');
            $table->string('operation_type_os')->nullable()->after('operation_type_od');
            
            $table->enum('operation_eye', ['OD', 'OS', 'OU'])->nullable()->after('operation_type_os');
            
            $table->decimal('cost', 10, 2)->default(0.00)->after('operation_eye');
            
            // Restore index
            $table->index('operation_type');
        });
    }
};
