<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorTheAnswersOfQuestionStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->boolean('answer')->default(false)->after('content')->index();
        });

        Schema::drop('answers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('answer');
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('option_id');

            $table->primary(['question_id', 'option_id']);

            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onUpdate('cascade');
        });
    }
}
