<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPapersAndPaperExamQuestionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('papers', 'exam_papers');

        Schema::table('paper_exam_question', function (Blueprint $table) {
            $table->dropForeign('paper_exam_question_paper_id_foreign');
        });

        Schema::rename('paper_exam_question', 'exam_paper_exam_question');

        Schema::table('exam_paper_exam_question', function (Blueprint $table) {
            $table->renameColumn('paper_id', 'exam_paper_id');

            $table->foreign('exam_paper_id')->references('id')->on('exam_papers')
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
        Schema::rename('exam_papers', 'papers');

        Schema::table('exam_paper_exam_question', function (Blueprint $table) {
            $table->dropForeign('exam_paper_exam_question_exam_paper_id_foreign');
        });

        Schema::rename('exam_paper_exam_question', 'paper_exam_question');

        Schema::table('paper_exam_question', function (Blueprint $table) {
            $table->renameColumn('exam_paper_id', 'paper_id');

            $table->foreign('paper_id')->references('id')->on('papers')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
