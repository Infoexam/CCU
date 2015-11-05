<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->integer('exam_question_id')->unsigned();
            $table->integer('exam_option_id')->unsigned();

            $table->primary(['exam_question_id', 'exam_option_id']);

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('exam_option_id')->references('id')->on('exam_options')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_answers', function (Blueprint $table) {
            $table->dropForeign('exam_answers_exam_question_id_foreign');
            $table->dropForeign('exam_answers_exam_option_id_foreign');
        });

        Schema::drop('exam_answers');
    }
}
