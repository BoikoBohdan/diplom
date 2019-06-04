<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUpdateDriverOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cancel_reason', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('additional_cancel_reason_id');
            $table->text('drivers_reason');
            $table->text('admins_reason');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('additional_cancel_reason_id')->references('id')->on('additional_cancel_reasons')->onDelete('CASCADE');

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
        Schema::dropIfExists('order_cancel_reason');
    }
}
