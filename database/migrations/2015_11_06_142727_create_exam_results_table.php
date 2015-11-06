<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_apply_id')->unsigned();
            $table->decimal('score', 6, 3)->nullable();
            $table->string('log', 65536)->nullable();
            $table->boolean('allow_re_sign_in')->default(false);
            $table->timestamp('signed_in_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index('score');

            $table->foreign('exam_apply_id')->references('id')->on('exam_applies')
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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign('exam_results_exam_apply_id_foreign');
        });

        Schema::drop('exam_results');
    }
}
