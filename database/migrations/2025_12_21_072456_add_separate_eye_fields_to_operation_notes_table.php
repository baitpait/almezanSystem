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
        Schema::table('operation_notes', function (Blueprint $table) {
            // PRK Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'prk_epithelial_removal_od')) {
                $table->enum('prk_epithelial_removal_od', ['Alcohol', 'Mechanical', 'Trans-PRK'])->nullable()->after('prk_bandage_contact_lens');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_epithelial_removal_os')) {
                $table->enum('prk_epithelial_removal_os', ['Alcohol', 'Mechanical', 'Trans-PRK'])->nullable()->after('prk_epithelial_removal_od');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_excimer_profile_od')) {
                $table->enum('prk_excimer_profile_od', ['Aspheric Front', 'Topography-guided'])->nullable()->after('prk_epithelial_removal_os');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_excimer_profile_os')) {
                $table->enum('prk_excimer_profile_os', ['Aspheric Front', 'Topography-guided'])->nullable()->after('prk_excimer_profile_od');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_mmc_0_02_percent_od')) {
                $table->boolean('prk_mmc_0_02_percent_od')->default(false)->after('prk_excimer_profile_os');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_mmc_0_02_percent_os')) {
                $table->boolean('prk_mmc_0_02_percent_os')->default(false)->after('prk_mmc_0_02_percent_od');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_bandage_contact_lens_od')) {
                $table->boolean('prk_bandage_contact_lens_od')->default(false)->after('prk_mmc_0_02_percent_os');
            }
            if (!Schema::hasColumn('operation_notes', 'prk_bandage_contact_lens_os')) {
                $table->boolean('prk_bandage_contact_lens_os')->default(false)->after('prk_bandage_contact_lens_od');
            }

            // Femto-LASIK Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'femto_flap_done_od')) {
                $table->boolean('femto_flap_done_od')->nullable()->after('femto_bandage_contact_lens');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_flap_done_os')) {
                $table->boolean('femto_flap_done_os')->nullable()->after('femto_flap_done_od');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_excimer_profile_od')) {
                $table->enum('femto_excimer_profile_od', ['Aspheric Front', 'Topography-guided'])->nullable()->after('femto_flap_done_os');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_excimer_profile_os')) {
                $table->enum('femto_excimer_profile_os', ['Aspheric Front', 'Topography-guided'])->nullable()->after('femto_excimer_profile_od');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_bandage_contact_lens_od')) {
                $table->boolean('femto_bandage_contact_lens_od')->default(false)->after('femto_excimer_profile_os');
            }
            if (!Schema::hasColumn('operation_notes', 'femto_bandage_contact_lens_os')) {
                $table->boolean('femto_bandage_contact_lens_os')->default(false)->after('femto_bandage_contact_lens_od');
            }

            // SMILE Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'smile_complete_lenticule_separation_od')) {
                $table->boolean('smile_complete_lenticule_separation_od')->nullable()->after('smile_complete_lenticule_extraction');
            }
            if (!Schema::hasColumn('operation_notes', 'smile_complete_lenticule_separation_os')) {
                $table->boolean('smile_complete_lenticule_separation_os')->nullable()->after('smile_complete_lenticule_separation_od');
            }
            if (!Schema::hasColumn('operation_notes', 'smile_complete_lenticule_extraction_od')) {
                $table->boolean('smile_complete_lenticule_extraction_od')->nullable()->after('smile_complete_lenticule_separation_os');
            }
            if (!Schema::hasColumn('operation_notes', 'smile_complete_lenticule_extraction_os')) {
                $table->boolean('smile_complete_lenticule_extraction_os')->nullable()->after('smile_complete_lenticule_extraction_od');
            }

            // PTK Fields - Separate for OD and OS
            if (!Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_od')) {
                $table->enum('ptk_epithelial_removal_od', ['Alcohol', 'Mechanical', 'Trans-PTK'])->nullable()->after('ptk_bandage_contact_lens');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_epithelial_removal_os')) {
                $table->enum('ptk_epithelial_removal_os', ['Alcohol', 'Mechanical', 'Trans-PTK'])->nullable()->after('ptk_epithelial_removal_od');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_excimer_profile_od')) {
                $table->enum('ptk_excimer_profile_od', ['Aspheric Front', 'Topography-guided'])->nullable()->after('ptk_epithelial_removal_os');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_excimer_profile_os')) {
                $table->enum('ptk_excimer_profile_os', ['Aspheric Front', 'Topography-guided'])->nullable()->after('ptk_excimer_profile_od');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_mmc_0_02_percent_od')) {
                $table->boolean('ptk_mmc_0_02_percent_od')->default(false)->after('ptk_excimer_profile_os');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_mmc_0_02_percent_os')) {
                $table->boolean('ptk_mmc_0_02_percent_os')->default(false)->after('ptk_mmc_0_02_percent_od');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_bandage_contact_lens_od')) {
                $table->boolean('ptk_bandage_contact_lens_od')->default(false)->after('ptk_mmc_0_02_percent_os');
            }
            if (!Schema::hasColumn('operation_notes', 'ptk_bandage_contact_lens_os')) {
                $table->boolean('ptk_bandage_contact_lens_os')->default(false)->after('ptk_bandage_contact_lens_od');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_notes', function (Blueprint $table) {
            $columnsToDrop = [
                // PRK
                'prk_epithelial_removal_od', 'prk_epithelial_removal_os',
                'prk_excimer_profile_od', 'prk_excimer_profile_os',
                'prk_mmc_0_02_percent_od', 'prk_mmc_0_02_percent_os',
                'prk_bandage_contact_lens_od', 'prk_bandage_contact_lens_os',
                // Femto
                'femto_flap_done_od', 'femto_flap_done_os',
                'femto_excimer_profile_od', 'femto_excimer_profile_os',
                'femto_bandage_contact_lens_od', 'femto_bandage_contact_lens_os',
                // Smile
                'smile_complete_lenticule_separation_od', 'smile_complete_lenticule_separation_os',
                'smile_complete_lenticule_extraction_od', 'smile_complete_lenticule_extraction_os',
                // PTK
                'ptk_epithelial_removal_od', 'ptk_epithelial_removal_os',
                'ptk_excimer_profile_od', 'ptk_excimer_profile_os',
                'ptk_mmc_0_02_percent_od', 'ptk_mmc_0_02_percent_os',
                'ptk_bandage_contact_lens_od', 'ptk_bandage_contact_lens_os',
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('operation_notes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
