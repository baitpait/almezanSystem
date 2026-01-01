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
        Schema::table('operations', function (Blueprint $table) {
            // Add separate operation_type fields for each eye
            if (!Schema::hasColumn('operations', 'operation_type_od')) {
                $table->string('operation_type_od')->nullable()->after('operation_type');
            }
            if (!Schema::hasColumn('operations', 'operation_type_os')) {
                $table->string('operation_type_os')->nullable()->after('operation_type_od');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            if (Schema::hasColumn('operations', 'operation_type_od')) {
                $table->dropColumn('operation_type_od');
            }
            if (Schema::hasColumn('operations', 'operation_type_os')) {
                $table->dropColumn('operation_type_os');
            }
        });
    }
};
