<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsInOrderCancelReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_cancel_reasons', function (Blueprint $table) {
            $table->unsignedInteger('additional_cancel_reason_id')->nullable()->change();
            $table->dropColumn('admins_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_cancel_reasons', function (Blueprint $table) {
            $table->unsignedInteger('additional_cancel_reason_id')->change();
            $table->text('admins_reason');
        });
    }
}
