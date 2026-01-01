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
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'photo',
                'email',
                'specialization',
                'notify_via_sms',
                'notify_via_email',
                'follow_up',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
            $table->string('email')->nullable()->unique()->after('photo');
            $table->string('specialization')->nullable()->after('email');
            $table->boolean('notify_via_sms')->default(false)->after('specialization');
            $table->boolean('notify_via_email')->default(false)->after('notify_via_sms');
            $table->boolean('follow_up')->default(false)->after('notify_via_email');
        });
    }
};
