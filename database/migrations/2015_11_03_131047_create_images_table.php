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
            $table->timestamp('uploaded_at');
            $table->integer('hash')->unsigned();
            $table->string('mime_type', 16);
            $table->integer('imageable_id')->unsigned();
            $table->string('imageable_type', 190);

            $table->primary(['uploaded_at', 'hash']);

            $table->index('imageable_id');
            $table->index('imageable_type');
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
