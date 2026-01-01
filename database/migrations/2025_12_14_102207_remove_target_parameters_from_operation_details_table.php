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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_details', function (Blueprint $table) {
            // OD (Right Eye) Target Parameters
            $table->string('target_nomogram_od')->nullable();
            $table->integer('target_pach_od')->nullable()->comment('Pachymetry OD');
            $table->string('target_kaapa_od')->nullable();
            $table->decimal('target_vertex_od', 5, 2)->nullable()->comment('Vertex distance OD');
            $table->decimal('target_wtw_od', 5, 2)->nullable()->comment('White to White OD');
            $table->enum('target_procedure_od', ['LASIK', 'PRK'])->nullable();
            $table->decimal('target_pta_od', 5, 2)->nullable()->comment('PTA% OD');
            $table->decimal('target_pupil_size_od', 5, 2)->nullable()->comment('Pupil size OD');
            
            // OS (Left Eye) Target Parameters
            $table->string('target_nomogram_os')->nullable();
            $table->integer('target_pach_os')->nullable()->comment('Pachymetry OS');
            $table->string('target_kaapa_os')->nullable();
            $table->decimal('target_vertex_os', 5, 2)->nullable()->comment('Vertex distance OS');
            $table->decimal('target_wtw_os', 5, 2)->nullable()->comment('White to White OS');
            $table->enum('target_procedure_os', ['LASIK', 'PRK'])->nullable();
            $table->decimal('target_pta_os', 5, 2)->nullable()->comment('PTA% OS');
            $table->decimal('target_pupil_size_os', 5, 2)->nullable()->comment('Pupil size OS');
            
            // Additional Target Parameters
            $table->string('target_add')->nullable()->comment('e.g., -0.25 Add');
        });
    }
};
