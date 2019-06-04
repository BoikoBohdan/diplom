<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSetAddressForLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('city');
            $table->string('country_code');
            $table->string('streetaddress');
            $table->dropColumn('address');
        });

        Schema::table('orders_locations', function (Blueprint $table) {
            $table->string('city');
            $table->string('country_code');
            $table->string('streetaddress');
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_locations', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('country_code');
            $table->dropColumn('streetaddress');
            $table->string('address');
        });
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('country_code');
            $table->dropColumn('streetaddress');
            $table->string('address');
        });
    }
}
