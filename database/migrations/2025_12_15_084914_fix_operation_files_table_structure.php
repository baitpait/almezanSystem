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
        // Check if table exists and has the wrong structure
        if (Schema::hasTable('operation_files')) {
            // Check if operation_id column exists
            if (!Schema::hasColumn('operation_files', 'operation_id')) {
                // Add all required columns
                Schema::table('operation_files', function (Blueprint $table) {
                    $table->foreignId('operation_id')->after('id')->constrained('operations')->onDelete('cascade');
                    $table->string('file_path')->after('operation_id');
                    $table->string('file_name')->after('file_path');
                    $table->string('file_type')->nullable()->after('file_name');
                    $table->string('mime_type')->nullable()->after('file_type');
                    $table->bigInteger('file_size')->nullable()->after('mime_type');
                    $table->text('description')->nullable()->after('file_size');
                    $table->enum('eye', ['OD', 'OS', 'OU'])->default('OU')->after('description');
                    
                    $table->index('operation_id');
                });
            }
        } else {
            // Create table if it doesn't exist
            Schema::create('operation_files', function (Blueprint $table) {
                $table->id();
                $table->foreignId('operation_id')->constrained('operations')->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('file_type')->nullable();
                $table->string('mime_type')->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->text('description')->nullable();
                $table->enum('eye', ['OD', 'OS', 'OU'])->default('OU');
                $table->timestamps();
                
                $table->index('operation_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table, just remove columns if needed
        if (Schema::hasTable('operation_files') && Schema::hasColumn('operation_files', 'operation_id')) {
            Schema::table('operation_files', function (Blueprint $table) {
                $table->dropForeign(['operation_id']);
                $table->dropColumn([
                    'operation_id',
                    'file_path',
                    'file_name',
                    'file_type',
                    'mime_type',
                    'file_size',
                    'description',
                    'eye'
                ]);
            });
        }
    }
};
