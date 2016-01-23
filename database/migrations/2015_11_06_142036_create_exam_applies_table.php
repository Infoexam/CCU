<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('exam_list_id');
            $table->unsignedInteger('apply_type_id');
            $table->timestamp('paid_at')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('exam_list_id');
            $table->index('apply_type_id');
            $table->index('created_at');
            $table->index('deleted_at');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade');
            $table->foreign('exam_list_id')->references('id')->on('exam_lists')
                ->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')
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
        Schema::table('exam_applies', function (Blueprint $table) {
            $table->dropForeign('exam_applies_user_id_foreign');
            $table->dropForeign('exam_applies_exam_list_id_foreign');
            $table->dropForeign('exam_applies_apply_type_id_foreign');
        });

        Schema::drop('exam_applies');
    }
}
