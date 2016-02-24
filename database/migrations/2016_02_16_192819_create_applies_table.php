<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->increments('id');


            // 關聯鍵.
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('listing_id')->index();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onUpdate('cascade');


            // 報名資訊.
            $table->char('type', 1)->default('S'); // S自行報名 / A管理員報名
            $table->timestamp('paid_at')->nullable();


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
        Schema::drop('applies');
    }
}
