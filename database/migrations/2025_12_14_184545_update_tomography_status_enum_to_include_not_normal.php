<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite compatibility, enum modifications are not needed
        // The column is already a string/varchar that accepts any value
        // This migration is primarily for MySQL enum constraints
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed for SQLite
    }
};
