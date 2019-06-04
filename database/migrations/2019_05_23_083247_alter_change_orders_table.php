<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChangeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('time_loading')->nullable()->change();
            $table->integer('time_dropping')->nullable()->change();
            $table->integer('time_break')->nullable()->change();
            $table->integer('load_type')->nullable()->change();
            $table->boolean('asap')->nullable()->change();
            $table->boolean('enforce_signature')->nullable()->change();
        });

        Schema::table('orders_locations', function (Blueprint $table) {
            $table->string('reference_id')->nullable()->change();
            $table->string('name')->nullable()->change();
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->integer('type')->nullable()->change();
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
            $table->integer('time_loading')->change();
            $table->integer('time_dropping')->change();
            $table->integer('time_break')->change();
            $table->integer('load_type')->change();
            $table->boolean('asap')->change();
            $table->boolean('enforce_signature')->change();
        });

        Schema::table('orders_locations', function (Blueprint $table) {
            $table->string('reference_id')->change();
            $table->string('name')->change();
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->integer('type')->change();
        });
    }
}
