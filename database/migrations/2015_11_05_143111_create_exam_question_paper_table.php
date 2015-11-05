<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamQuestionPaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_question_paper', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paper_id')->unsigned();
            $table->integer('exam_question_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['paper_id', 'exam_question_id', 'deleted_at']);

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
        Schema::table('exam_question_paper', function (Blueprint $table) {
            $table->dropForeign('exam_question_paper_exam_question_id_foreign');
            $table->dropForeign('exam_question_paper_paper_id_foreign');
        });

        Schema::drop('exam_question_paper');
    }
}
