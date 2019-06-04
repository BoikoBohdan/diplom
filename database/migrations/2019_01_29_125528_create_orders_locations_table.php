<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('reference_id');
            $table->string('name');
            $table->integer('type');
            $table->string('phone', 50);
            $table->string('address');
            $table->string('postcode', 20);
            $table->string('contact_name');
            $table->text('note')->nullable();
            $table->foreign(['order_id'])->references('id')
                ->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_locations');
    }
}
