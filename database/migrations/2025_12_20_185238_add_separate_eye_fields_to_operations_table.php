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
            // PRK Fields - Separate for OD and OS
            if (!Schema::hasColumn('operations', 'prk_epithelial_removal_od')) {
                $table->string('prk_epithelial_removal_od')->nullable()->after('prk_target');
            }
            if (!Schema::hasColumn('operations', 'prk_epithelial_removal_os')) {
                $table->string('prk_epithelial_removal_os')->nullable()->after('prk_epithelial_removal_od');
            }
            if (!Schema::hasColumn('operations', 'prk_excimer_profile_od')) {
                $table->string('prk_excimer_profile_od')->nullable()->after('prk_epithelial_removal_os');
            }
            if (!Schema::hasColumn('operations', 'prk_excimer_profile_os')) {
                $table->string('prk_excimer_profile_os')->nullable()->after('prk_excimer_profile_od');
            }
            if (!Schema::hasColumn('operations', 'prk_monovision_eye_od')) {
                $table->enum('prk_monovision_eye_od', ['OD', 'OS'])->nullable()->after('prk_excimer_profile_os');
            }
            if (!Schema::hasColumn('operations', 'prk_monovision_eye_os')) {
                $table->enum('prk_monovision_eye_os', ['OD', 'OS'])->nullable()->after('prk_monovision_eye_od');
            }
            if (!Schema::hasColumn('operations', 'prk_target_od')) {
                $table->string('prk_target_od')->nullable()->after('prk_monovision_eye_os');
            }
            if (!Schema::hasColumn('operations', 'prk_target_os')) {
                $table->string('prk_target_os')->nullable()->after('prk_target_od');
            }

            // Femto Lasik Fields - Separate for OD and OS
            if (!Schema::hasColumn('operations', 'femto_excimer_profile_od')) {
                $table->string('femto_excimer_profile_od')->nullable()->after('femto_target');
            }
            if (!Schema::hasColumn('operations', 'femto_excimer_profile_os')) {
                $table->string('femto_excimer_profile_os')->nullable()->after('femto_excimer_profile_od');
            }
            if (!Schema::hasColumn('operations', 'femto_monovision_eye_od')) {
                $table->enum('femto_monovision_eye_od', ['OD', 'OS'])->nullable()->after('femto_excimer_profile_os');
            }
            if (!Schema::hasColumn('operations', 'femto_monovision_eye_os')) {
                $table->enum('femto_monovision_eye_os', ['OD', 'OS'])->nullable()->after('femto_monovision_eye_od');
            }
            if (!Schema::hasColumn('operations', 'femto_target_od')) {
                $table->string('femto_target_od')->nullable()->after('femto_monovision_eye_os');
            }
            if (!Schema::hasColumn('operations', 'femto_target_os')) {
                $table->string('femto_target_os')->nullable()->after('femto_target_od');
            }

            // Smile Fields - Separate for OD and OS
            if (!Schema::hasColumn('operations', 'smile_monovision_eye_od')) {
                $table->enum('smile_monovision_eye_od', ['OD', 'OS'])->nullable()->after('smile_target');
            }
            if (!Schema::hasColumn('operations', 'smile_monovision_eye_os')) {
                $table->enum('smile_monovision_eye_os', ['OD', 'OS'])->nullable()->after('smile_monovision_eye_od');
            }
            if (!Schema::hasColumn('operations', 'smile_target_od')) {
                $table->string('smile_target_od')->nullable()->after('smile_monovision_eye_os');
            }
            if (!Schema::hasColumn('operations', 'smile_target_os')) {
                $table->string('smile_target_os')->nullable()->after('smile_target_od');
            }

            // PTK Fields - Separate for OD and OS
            if (!Schema::hasColumn('operations', 'ptk_epithelial_removal_od')) {
                $table->string('ptk_epithelial_removal_od')->nullable()->after('ptk_target');
            }
            if (!Schema::hasColumn('operations', 'ptk_epithelial_removal_os')) {
                $table->string('ptk_epithelial_removal_os')->nullable()->after('ptk_epithelial_removal_od');
            }
            if (!Schema::hasColumn('operations', 'ptk_excimer_profile_od')) {
                $table->string('ptk_excimer_profile_od')->nullable()->after('ptk_epithelial_removal_os');
            }
            if (!Schema::hasColumn('operations', 'ptk_excimer_profile_os')) {
                $table->string('ptk_excimer_profile_os')->nullable()->after('ptk_excimer_profile_od');
            }
            if (!Schema::hasColumn('operations', 'ptk_monovision_eye_od')) {
                $table->enum('ptk_monovision_eye_od', ['OD', 'OS'])->nullable()->after('ptk_excimer_profile_os');
            }
            if (!Schema::hasColumn('operations', 'ptk_monovision_eye_os')) {
                $table->enum('ptk_monovision_eye_os', ['OD', 'OS'])->nullable()->after('ptk_monovision_eye_od');
            }
            if (!Schema::hasColumn('operations', 'ptk_target_od')) {
                $table->string('ptk_target_od')->nullable()->after('ptk_monovision_eye_os');
            }
            if (!Schema::hasColumn('operations', 'ptk_target_os')) {
                $table->string('ptk_target_os')->nullable()->after('ptk_target_od');
            }

            // Incompatible Fields - Separate for OD and OS
            if (!Schema::hasColumn('operations', 'incompatible_notes_od')) {
                $table->text('incompatible_notes_od')->nullable()->after('incompatible_notes');
            }
            if (!Schema::hasColumn('operations', 'incompatible_notes_os')) {
                $table->text('incompatible_notes_os')->nullable()->after('incompatible_notes_od');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $columnsToDrop = [
                // PRK
                'prk_epithelial_removal_od', 'prk_epithelial_removal_os',
                'prk_excimer_profile_od', 'prk_excimer_profile_os',
                'prk_monovision_eye_od', 'prk_monovision_eye_os',
                'prk_target_od', 'prk_target_os',
                // Femto
                'femto_excimer_profile_od', 'femto_excimer_profile_os',
                'femto_monovision_eye_od', 'femto_monovision_eye_os',
                'femto_target_od', 'femto_target_os',
                // Smile
                'smile_monovision_eye_od', 'smile_monovision_eye_os',
                'smile_target_od', 'smile_target_os',
                // PTK
                'ptk_epithelial_removal_od', 'ptk_epithelial_removal_os',
                'ptk_excimer_profile_od', 'ptk_excimer_profile_os',
                'ptk_monovision_eye_od', 'ptk_monovision_eye_os',
                'ptk_target_od', 'ptk_target_os',
                // Incompatible
                'incompatible_notes_od', 'incompatible_notes_os',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
