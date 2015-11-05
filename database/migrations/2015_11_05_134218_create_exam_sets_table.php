<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->integer('category_id')->unsigned();
            $table->boolean('enable')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('category_id');
            $table->index('enable');
            $table->index('deleted_at');

            $table->foreign('category_id')->references('id')->on('categories')
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
        Schema::table('exam_sets', function (Blueprint $table) {
            $table->dropForeign('exam_sets_category_id_foreign');
        });

        Schema::drop('exam_sets');
    }
}