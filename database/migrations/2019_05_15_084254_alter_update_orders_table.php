<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('group_guid')->nullable()->change();
            $table->time('pickup_time_to')->nullable()->change();
            $table->time('dropoff_time_to')->nullable()->change();
            $table->integer('shipment_type')->nullable()->change();
        });

        Schema::table('orders_locations', function (Blueprint $table) {
            $table->string('country_code')->nullable()->change();
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->string('reference_id')->nullable()->change();
            $table->integer('unit_type')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('group_guid')->change();
            $table->time('pickup_time_to')->change();
            $table->time('dropoff_time_to')->change();
            $table->integer('shipment_type')->change();
        });

        Schema::table('orders_locations', function (Blueprint $table) {
            $table->string('country_code')->change();
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->string('reference_id')->change();
            $table->integer('unit_type')->change();
            $table->integer('quantity')->change();
        });
    }
}
