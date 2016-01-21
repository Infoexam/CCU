<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamExplanationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_explanations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_question_id');
            $table->string('content', 1000);
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_question_id');
            $table->index('deleted_at');

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')
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
        Schema::table('exam_explanations', function (Blueprint $table) {
            $table->dropForeign('exam_explanations_exam_question_id_foreign');
        });

        Schema::drop('exam_explanations');
    }
}
