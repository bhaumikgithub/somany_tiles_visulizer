<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_pdf_data', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });

        DB::table('user_pdf_data')->get()->each(function($user) {
            // Split the old 'name' into 'first_name' and 'last_name'
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Update the table with the split data
            DB::table('user_pdf_data')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_pdf_data', function (Blueprint $table) {
            Schema::table('user_pdf_data', function (Blueprint $table) {
                // Drop the new fields
                $table->dropColumn(['first_name', 'last_name', 'state', 'city']);
            });
        });
    }
};
