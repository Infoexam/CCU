<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('receipt_no', 16);
            $table->char('receipt_date', 7);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->nullableTimestamps();

            $table->unique('receipt_no');

            $table->index('user_id');
            $table->index('category_id');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign('receipts_user_id_foreign');
            $table->dropForeign('receipts_category_id_foreign');
        });

        Schema::drop('receipts');
    }
}
