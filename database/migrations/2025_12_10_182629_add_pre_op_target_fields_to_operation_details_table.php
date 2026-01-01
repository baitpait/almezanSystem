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
        Schema::table('operation_details', function (Blueprint $table) {
            // Target Parameters for Pre-op (from Pre op final PDF)
            // OD (Right Eye) Target Parameters
            $table->string('target_nomogram_od')->nullable()->after('target_refraction_od');
            $table->integer('target_pach_od')->nullable()->after('target_nomogram_od')->comment('Pachymetry OD');
            $table->string('target_kaapa_od')->nullable()->after('target_pach_od');
            $table->decimal('target_vertex_od', 5, 2)->nullable()->after('target_kaapa_od')->comment('Vertex distance OD');
            $table->decimal('target_wtw_od', 5, 2)->nullable()->after('target_vertex_od')->comment('White to White OD');
            $table->enum('target_procedure_od', ['LASIK', 'PRK'])->nullable()->after('target_wtw_od');
            $table->decimal('target_pta_od', 5, 2)->nullable()->after('target_procedure_od')->comment('PTA% OD');
            $table->decimal('target_pupil_size_od', 5, 2)->nullable()->after('target_pta_od')->comment('Pupil size OD');
            
            // OS (Left Eye) Target Parameters
            $table->string('target_nomogram_os')->nullable()->after('target_pupil_size_od');
            $table->integer('target_pach_os')->nullable()->after('target_nomogram_os')->comment('Pachymetry OS');
            $table->string('target_kaapa_os')->nullable()->after('target_pach_os');
            $table->decimal('target_vertex_os', 5, 2)->nullable()->after('target_kaapa_os')->comment('Vertex distance OS');
            $table->decimal('target_wtw_os', 5, 2)->nullable()->after('target_vertex_os')->comment('White to White OS');
            $table->enum('target_procedure_os', ['LASIK', 'PRK'])->nullable()->after('target_wtw_os');
            $table->decimal('target_pta_os', 5, 2)->nullable()->after('target_procedure_os')->comment('PTA% OS');
            $table->decimal('target_pupil_size_os', 5, 2)->nullable()->after('target_pta_os')->comment('Pupil size OS');
            
            // Additional Target Parameters
            $table->string('target_add')->nullable()->after('target_pupil_size_os')->comment('e.g., -0.25 Add');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_details', function (Blueprint $table) {
            $table->dropColumn([
                'target_nomogram_od',
                'target_pach_od',
                'target_kaapa_od',
                'target_vertex_od',
                'target_wtw_od',
                'target_procedure_od',
                'target_pta_od',
                'target_pupil_size_od',
                'target_nomogram_os',
                'target_pach_os',
                'target_kaapa_os',
                'target_vertex_os',
                'target_wtw_os',
                'target_procedure_os',
                'target_pta_os',
                'target_pupil_size_os',
                'target_add',
            ]);
        });
    }
};
