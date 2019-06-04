<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('order_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('order_group_id')->nullable();
            $table->foreign('order_group_id')->references('id')->on('order_groups')->onDelete('CASCADE');
        });

        Schema::create('waypoints', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->tinyInteger('type');
            $table->integer('number');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('waypoints');

        if (Schema::hasColumn('orders', 'group_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['order_group_id']);
                $table->dropColumn('order_group_id');
            });
        }

        Schema::dropIfExists('order_groups');
    }
}
