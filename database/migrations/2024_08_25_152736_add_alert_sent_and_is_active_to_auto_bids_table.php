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
        Schema::table('auto_bids', function (Blueprint $table) {
            $table->boolean('alert_sent')->default(false)->after('bid_alert_percentage');
            $table->boolean('is_active')->default(true)->after('alert_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auto_bids', function (Blueprint $table) {
            $table->dropColumn('alert_sent');
            $table->dropColumn('is_active');
        });
    }
};
