<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperExamQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_exam_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paper_id')->unsigned();
            $table->integer('exam_question_id')->unsigned();

            $table->unique(['paper_id', 'exam_question_id']);

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('paper_id')->references('id')->on('papers')
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
        Schema::table('paper_exam_question', function (Blueprint $table) {
            $table->dropForeign('paper_exam_question_exam_question_id_foreign');
            $table->dropForeign('paper_exam_question_paper_id_foreign');
        });

        Schema::drop('paper_exam_question');
    }
}
