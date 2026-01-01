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
            // Add decision fields for each eye (nullable for backward compatibility)
            $table->string('decision_od')->nullable()->after('decision')->comment('Decision for Right Eye (OD)');
            $table->string('decision_os')->nullable()->after('decision_od')->comment('Decision for Left Eye (OS)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn(['decision_od', 'decision_os']);
        });
    }
};
