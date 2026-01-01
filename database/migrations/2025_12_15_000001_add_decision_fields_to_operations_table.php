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
            $table->string('decision')->nullable()->after('target');
            $table->string('prk_epithelial_removal')->nullable()->after('decision');
            $table->string('prk_excimer_profile')->nullable()->after('prk_epithelial_removal');
            $table->string('prk_monovision_eye')->nullable()->after('prk_excimer_profile');
            $table->string('prk_target')->nullable()->after('prk_monovision_eye');

            $table->string('femto_excimer_profile')->nullable()->after('prk_target');
            $table->boolean('femto_monovision')->nullable()->after('femto_excimer_profile');

            $table->boolean('smile_monovision')->nullable()->after('femto_monovision');

            $table->boolean('ptk_topo_guided')->nullable()->after('smile_monovision');

            $table->text('incompatible_notes')->nullable()->after('ptk_topo_guided');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn([
                'decision',
                'prk_epithelial_removal',
                'prk_excimer_profile',
                'prk_monovision_eye',
                'prk_target',
                'femto_excimer_profile',
                'femto_monovision',
                'smile_monovision',
                'ptk_topo_guided',
                'incompatible_notes',
            ]);
        });
    }
};
