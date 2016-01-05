<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_question_id')->unsigned();
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
        Schema::table('exam_options', function (Blueprint $table) {
            $table->dropForeign('exam_options_exam_question_id_foreign');
        });

        Schema::drop('exam_options');
    }
}
