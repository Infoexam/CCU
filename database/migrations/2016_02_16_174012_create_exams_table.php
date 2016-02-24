<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('category_id')->index();

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');


            // 題庫資訊.
            $table->string('name', 16);
            $table->boolean('enable')->default(false)->index();


            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exams');
    }
}
