<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->string('group_guid');
            $table->string('reference_id');
            $table->integer('fee');
            $table->integer('status');
            $table->integer('time_loading');
            $table->integer('time_dropping');
            $table->integer('time_break');
            $table->integer('load_type');
            $table->integer('payment_type');
            $table->integer('shipment_type');
            $table->boolean('asap');
            $table->boolean('enforce_signature');
            $table->text('notes')->nullable();
            $table->text('customer_info')->nullable();
            $table->text('payment_info')->nullable();
            $table->date('pickup_date');
            $table->time('pickup_time_from');
            $table->time('pickup_time_to');
            $table->date('dropoff_date');
            $table->time('dropoff_time_from');
            $table->time('dropoff_time_to');

            $table->foreign(['driver_id'])->references('id')
                ->on('drivers')->onDelete('cascade');
            $table->timestamps();
            $table->foreign(['restaurant_id'])->references('id')
                ->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
