<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->increments('id');


            // 試卷資料.
            $table->string('name', 16);
            $table->string('remark', 190)->nullable();
            $table->boolean('automatic')->default(false)->index();


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
        Schema::drop('papers');
    }
}
