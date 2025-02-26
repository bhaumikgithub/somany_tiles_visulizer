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
        Schema::create('room_ais', function (Blueprint $table) {
            $table->id();
            $table->string('name', '255');
            $table->string('type', '32')->nullable();
            $table->string('icon', '255')->nullable();
            $table->string('image', '1000');
            $table->string('shadow', '1000');
            $table->string('shadow_matt', '1000');
            $table->text('surfaces');
            $table->boolean('enabled')->default(true)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_ais');
    }
};
