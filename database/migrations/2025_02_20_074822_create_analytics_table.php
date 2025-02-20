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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->json('pincode')->nullable();
            $table->json('zone')->nullable();
            $table->json('category')->nullable(); // Ensure category is stored as JSON
            $table->json('room')->nullable(); // Ensure category is stored as JSON
            $table->json('viewed_tiles')->nullable();
            $table->json('used_tiles')->nullable();
            $table->json('tile_usage_count')->nullable(); // Stores count of each used tile
            $table->timestamp('visited_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('unique_cart_id')->nullable();
            $table->string('user_logged_in')->nullable();
            $table->string('downloaded_pdf')->nullable();
            $table->string('showroom')->nullable();
            $table->timestamps(); // This adds `created_at` and `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
