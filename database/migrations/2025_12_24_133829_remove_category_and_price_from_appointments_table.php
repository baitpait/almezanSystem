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
        Schema::table('appointments', function (Blueprint $table) {
            // Drop foreign key constraint first
            if (Schema::hasColumn('appointments', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            
            // Drop price column
            if (Schema::hasColumn('appointments', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('doctor_id')->constrained('categories')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('appointments', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('category_id')->default(0.00);
            }
        });
    }
};
