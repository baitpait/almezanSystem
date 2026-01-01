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
        // Update ptk_epithelial_removal enum to use Trans-PTK instead of Trans-PRK
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal ENUM('Alcohol', 'Mechanical', 'Trans-PTK') NULL");
        }
        
        // Update ptk_epithelial_removal_od enum
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_od')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal_od ENUM('Alcohol', 'Mechanical', 'Trans-PTK') NULL");
        }
        
        // Update ptk_epithelial_removal_os enum
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_os')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal_os ENUM('Alcohol', 'Mechanical', 'Trans-PTK') NULL");
        }
        
        // Update any existing 'Trans-PRK' values to 'Trans-PTK' for PTK fields
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal', 'Trans-PRK')
            ->update(['ptk_epithelial_removal' => 'Trans-PTK']);
            
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal_od', 'Trans-PRK')
            ->update(['ptk_epithelial_removal_od' => 'Trans-PTK']);
            
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal_os', 'Trans-PRK')
            ->update(['ptk_epithelial_removal_os' => 'Trans-PTK']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any 'Trans-PTK' values back to 'Trans-PRK' for PTK fields
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal', 'Trans-PTK')
            ->update(['ptk_epithelial_removal' => 'Trans-PRK']);
            
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal_od', 'Trans-PTK')
            ->update(['ptk_epithelial_removal_od' => 'Trans-PRK']);
            
        DB::table('operation_notes')
            ->where('ptk_epithelial_removal_os', 'Trans-PTK')
            ->update(['ptk_epithelial_removal_os' => 'Trans-PRK']);
        
        // Revert enum back to Trans-PRK
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal ENUM('Alcohol', 'Mechanical', 'Trans-PRK') NULL");
        }
        
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_od')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal_od ENUM('Alcohol', 'Mechanical', 'Trans-PRK') NULL");
        }
        
        if (Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_os')) {
            DB::statement("ALTER TABLE operation_notes MODIFY COLUMN ptk_epithelial_removal_os ENUM('Alcohol', 'Mechanical', 'Trans-PRK') NULL");
        }
    }
};
