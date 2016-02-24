<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('option_id');

            $table->primary(['question_id', 'option_id']);

            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answers');
    }
}
