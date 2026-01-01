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
            // Drop old smile_monovision boolean field (if exists)
            if (Schema::hasColumn('operations', 'smile_monovision')) {
                $table->dropColumn('smile_monovision');
            }
            
            // Add new fields for SMILE (similar to PRK and Femto Lasik) - check if not exists
            if (!Schema::hasColumn('operations', 'smile_monovision_eye')) {
                $table->enum('smile_monovision_eye', ['OD', 'OS'])->nullable()->after('femto_target');
            }
            if (!Schema::hasColumn('operations', 'smile_target')) {
                $table->string('smile_target')->nullable()->after('smile_monovision_eye');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Drop new fields
            if (Schema::hasColumn('operations', 'smile_monovision_eye')) {
                $table->dropColumn('smile_monovision_eye');
            }
            if (Schema::hasColumn('operations', 'smile_target')) {
                $table->dropColumn('smile_target');
            }
            
            // Re-add old field
            if (!Schema::hasColumn('operations', 'smile_monovision')) {
                $table->boolean('smile_monovision')->nullable()->after('femto_target');
            }
        });
    }
};
