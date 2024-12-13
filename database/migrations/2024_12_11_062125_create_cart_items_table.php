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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->string('room_name',255)->nullable();
            $table->string('room_type',255)->nullable();
            $table->string('current_room_design',255)->nullable();
            $table->string('current_room_thumbnail',255)->nullable();
            $table->text('tiles_json')->nullable();
            $table->integer('cart_id');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('room_id')->references('id')->on('room2ds')->onDelete('cascade');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
