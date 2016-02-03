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
            $table->unsignedInteger('hash');
            $table->string('mime_type', 16);
            $table->unsignedInteger('imageable_id');
            $table->string('imageable_type', 190);

            $table->primary(['uploaded_at', 'hash']);

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
