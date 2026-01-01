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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'service_id')) {
                $table->foreignId('service_id')->nullable()->after('patient_id')->constrained('services')->nullOnDelete();
                $table->index('service_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropIndex(['service_id']);
                $table->dropColumn('service_id');
            }
        });
    }
};
