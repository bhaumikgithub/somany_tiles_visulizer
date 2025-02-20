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
            $table->string('pincode')->nullable();
            $table->string('zone')->nullable();
            $table->string('category')->nullable();
            $table->string('room')->nullable();
            $table->json('viewed_tiles')->nullable();
            $table->json('used_tiles')->nullable();
            $table->json('tile_usage_count')->nullable(); // Stores count of each used tile
            $table->timestamp('visited_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('user_logged_in')->default(false)->nullable();
            $table->boolean('downloaded_pdf')->default(false)->nullable();
            $table->string('showroom')->nullable();
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
