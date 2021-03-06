<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_set_id');
            $table->string('content', 1000);
            $table->unsignedInteger('difficulty_id');
            $table->boolean('multiple');
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('exam_set_id');
            $table->index('difficulty_id');
            $table->index('multiple');
            $table->index('deleted_at');

            $table->foreign('exam_set_id')->references('id')->on('exam_sets')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('difficulty_id')->references('id')->on('categories')
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
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropForeign('exam_questions_exam_set_id_foreign');
            $table->dropForeign('exam_questions_difficulty_id_foreign');
        });

        Schema::drop('exam_questions');
    }
}
