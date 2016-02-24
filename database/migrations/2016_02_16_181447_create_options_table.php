<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('question_id')->index();

            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');


            // 選項資訊.
            $table->string('content', 1000)->nullable();


            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('options');
    }
}
