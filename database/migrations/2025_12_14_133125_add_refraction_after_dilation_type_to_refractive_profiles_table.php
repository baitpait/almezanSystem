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
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->enum('refraction_after_dilation_type', ['Mydramide', 'CYCLO'])->nullable()->after('refraction_after_dilation_os_vision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refractive_profiles', function (Blueprint $table) {
            $table->dropColumn('refraction_after_dilation_type');
        });
    }
};
