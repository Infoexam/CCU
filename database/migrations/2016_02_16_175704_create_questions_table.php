<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('exam_id')->index();

            $table->foreign('exam_id')->references('id')->on('exams')->onUpdate('cascade');


            // 題目資訊.
            $table->string('content', 1000)->nullable();
            $table->boolean('multiple')->default(false)->index();
            $table->unsignedInteger('difficulty_id')->index();
            $table->string('explanation', 1000)->nullable();
            $table->unsignedInteger('question_id')->nullable()->index();

            $table->foreign('difficulty_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');


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
        Schema::drop('questions');
    }
}
