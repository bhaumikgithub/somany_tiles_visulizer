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
        Schema::table('tiles', function (Blueprint $table) {
            $table->string('size')->nullable()->after('height');
            $table->string('image_variation_1')->nullable()->after('file');
            $table->string('image_variation_2')->nullable()->after('image_variation_1');
            $table->string('sku')->nullable()->unique()->after('enabled');
            $table->string('application_room_area')->after('sku')->nullable();
            $table->string('brand')->nullable()->after('application_room_area');
            $table->string('sub_brand_1')->nullable()->after('brand');
            $table->string('color')->nullable()->after('sub_brand_1');
            $table->string('poc')->nullable()->after('color');
            $table->decimal('thickness', 8, 2)->nullable()->after('poc');
            $table->integer('tiles_per_carton')->nullable()->after('thickness');
            $table->string('avg_wt_per_carton')->nullable()->after('tiles_per_carton');
            $table->string('coverage_sq_ft')->nullable()->after('avg_wt_per_carton');
            $table->string('coverage_sq_mt')->nullable()->after('coverage_sq_ft');
            $table->string('pattern')->nullable()->after('coverage_sq_mt');
            $table->string('plant')->nullable()->after('pattern');
            $table->string('service_geography')->nullable()->after('plant');
            $table->string('unit_of_production')->nullable()->after('service_geography');
            $table->string('yes_no')->nullable()->after('unit_of_production');
            $table->string('record_creation_time')->nullable()->after('yes_no');
            $table->string('deletion')->nullable()->after('record_creation_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->dropColumn(['size','image_variation_1','image_variation_2','sku','application_room_area','brand','sub_brand_1',
                'color','poc','thickness','tiles_per_carton','avg_wt_per_carton','coverage_sq_ft','coverage_sq_mt','pattern',
                'plant','service_geography','unit_of_production','yes_no','record_creation_time','deletion']);
        });
    }
};
