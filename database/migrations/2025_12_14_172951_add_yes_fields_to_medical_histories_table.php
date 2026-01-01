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
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->boolean('family_history_ocular_disease_yes')->default(false)->after('family_history_ocular_disease');
            $table->boolean('current_medications_yes')->default(false)->after('current_medications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_histories', function (Blueprint $table) {
            $table->dropColumn(['family_history_ocular_disease_yes', 'current_medications_yes']);
        });
    }
};
