<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanceledOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancel_reasons', function(Blueprint $table) {
            $table->increments('id');
            $table->text('info');
        });

        Schema::create('additional_cancel_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cancel_reason_id');
            $table->text('info');

            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('CASCADE');

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('additional_cancel_reasons');
        Schema::dropIfExists('cancel_reasons');
    }
}
