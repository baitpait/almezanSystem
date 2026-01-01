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
            // Remove general recommendation fields (check if exists first)
            $columnsToDrop = ['excimer_profile', 'topographic_guided_profile', 'presbyopia_profile', 'target', 'mv_eye'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Re-add fields if rollback is needed
            if (!Schema::hasColumn('operations', 'excimer_profile')) {
                $table->string('excimer_profile')->nullable()->after('recommendation_notes');
            }
            if (!Schema::hasColumn('operations', 'topographic_guided_profile')) {
                $table->string('topographic_guided_profile')->nullable()->after('excimer_profile');
            }
            if (!Schema::hasColumn('operations', 'presbyopia_profile')) {
                $table->string('presbyopia_profile')->nullable()->after('topographic_guided_profile');
            }
            if (!Schema::hasColumn('operations', 'target')) {
                $table->string('target')->nullable()->after('presbyopia_profile');
            }
            if (!Schema::hasColumn('operations', 'mv_eye')) {
                $table->enum('mv_eye', ['OD', 'OS'])->nullable()->after('target');
            }
        });
    }
};
