<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVehiclesAddVehicleTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_type_id');
            $table->foreign('vehicle_type_id')->references('id')
                ->on('vehicle_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('vehicles', 'vehicle_type_id')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropForeign(['vehicle_type_id']);
                $table->dropColumn('vehicle_type_id');
            });
        }
    }
}
