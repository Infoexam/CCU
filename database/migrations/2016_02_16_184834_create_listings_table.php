<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');


            // 測驗時間與地點資訊.
            $table->char('code', 15)->unique();
            $table->timestamp('began_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->unsignedTinyInteger('duration')->default(90);
            $table->timestamp('started_at')->nullable();
            $table->string('room', 8);


            // 測驗內容與報名資訊.
            $table->boolean('applicable')->default(false)->index();
            $table->unsignedInteger('paper_id')->nullable()->index();
            $table->unsignedInteger('apply_type_id')->index();
            $table->unsignedInteger('subject_id')->index();

            $table->foreign('paper_id')->references('id')->on('papers')->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('categories')->onUpdate('cascade');


            // 測驗人數資訊.
            $table->unsignedTinyInteger('maximum_num');
            $table->unsignedTinyInteger('applied_num')->default(0);
            $table->unsignedTinyInteger('tested_num')->default(0);


            // 測驗題目備份.
            $table->string('log', 16000)->nullable();


            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('listings');
    }
}
