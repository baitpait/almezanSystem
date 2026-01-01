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
            $table->dropColumn([
                'dry_eye_treatment',
                'dry_eye_follow_up',
                'corneal_warpage',
                'corneal_warpage_follow_up'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->boolean('dry_eye_treatment')->default(false)->after('post_op_notes');
            $table->text('dry_eye_follow_up')->nullable()->after('dry_eye_treatment');
            $table->boolean('corneal_warpage')->default(false)->after('dry_eye_follow_up');
            $table->text('corneal_warpage_follow_up')->nullable()->after('corneal_warpage');
        });
    }
};
