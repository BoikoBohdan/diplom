<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id');
            $table->unsignedInteger('order_id');
            $table->string('name');
            $table->integer('unit_type');
            $table->integer('quantity');
            $table->text('note')->nullable();
            $table->integer('fee');
            $table->integer('type');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('orders_products');
    }
}
