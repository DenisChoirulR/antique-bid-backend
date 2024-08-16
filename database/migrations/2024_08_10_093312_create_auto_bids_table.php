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
        Schema::create('auto_bids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('max_bid_amount', 15, 2);
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('item_id')->constrained()->onDelete('cascade');
            $table->integer('bid_alert_percentage')->default(90); // Default 90%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_bids');
    }
};
