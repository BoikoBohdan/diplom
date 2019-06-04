<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuidToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('guid')->index();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('group_guid')->references('guid')->on('companies')->onDelete('CASCADE')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('group_guid')->change();
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('guid');
        });
    }
}
