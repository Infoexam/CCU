<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');

            $table->unique(['user_id', 'category_id']);

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');


            // 測驗資訊.
            $table->decimal('score', 6, 3)->nullable();
            $table->unsignedTinyInteger('free')->default(0);


            // Timestamps.
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
        Schema::drop('certificates');
    }
}
