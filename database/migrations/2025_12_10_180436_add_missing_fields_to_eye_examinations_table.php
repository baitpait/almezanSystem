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
        Schema::table('eye_examinations', function (Blueprint $table) {
            // IOP (Intraocular Pressure) - missing from PDF
            $table->string('od_iop')->nullable()->after('od_lids')->comment('Intraocular Pressure OD');
            $table->string('os_iop')->nullable()->after('os_lids')->comment('Intraocular Pressure OS');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eye_examinations', function (Blueprint $table) {
            $table->dropColumn(['od_iop', 'os_iop']);
        });
    }
};
