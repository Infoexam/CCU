<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('apply_id')->index();

            $table->foreign('apply_id')->references('id')->on('applies')->onUpdate('cascade');


            // 測驗資訊.
            $table->unsignedTinyInteger('duration');
            $table->boolean('re_sign_in')->default(false);


            // 測驗結果.
            $table->timestamp('signed_in_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 6, 3)->nullable();
            $table->string('log', 16000)->nullable();


            // Timestamps.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('results');
    }
}
