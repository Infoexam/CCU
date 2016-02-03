<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 13);
            $table->timestamp('began_at');
            $table->unsignedTinyInteger('duration');
            $table->string('room', 8);
            $table->unsignedInteger('paper_id')->nullable();
            $table->unsignedInteger('apply_type_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedTinyInteger('std_maximum_num');
            $table->unsignedTinyInteger('std_apply_num')->default(0);
            $table->unsignedTinyInteger('std_test_num')->default(0);
            $table->boolean('allow_apply')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unique('code');

            $table->index('began_at');
            $table->index('room');
            $table->index('paper_id');
            $table->index('apply_type_id');
            $table->index('subject_id');
            $table->index('created_at');
            $table->index('deleted_at');

            $table->foreign('paper_id')->references('id')->on('papers')
                ->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')
                ->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('categories')
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
        Schema::table('exam_lists', function (Blueprint $table) {
            $table->dropForeign('exam_lists_paper_id_foreign');
            $table->dropForeign('exam_lists_apply_type_id_foreign');
            $table->dropForeign('exam_lists_subject_id_foreign');
        });

        Schema::drop('exam_lists');
    }
}
