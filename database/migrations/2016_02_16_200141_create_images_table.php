<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            // 圖片資訊.
            $table->unsignedBigInteger('hash')->primary();
            $table->string('extension', 12);
            $table->timestamp('uploaded_at');


            // Morphs.
            $table->unsignedInteger('imageable_id');
            $table->string('imageable_type', 180);

            $table->index(['imageable_id', 'imageable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
