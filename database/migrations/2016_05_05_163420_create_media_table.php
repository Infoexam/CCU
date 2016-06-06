<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::drop('images');

        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('model_id');
            $table->string('model_type', 190);
            $table->index(['model_id', 'model_type']);
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->text('manipulations');
            $table->text('custom_properties');
            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('media');

        Schema::create('images', function (Blueprint $table) {
            $table->unsignedBigInteger('hash')->primary();
            $table->string('extension', 12);
            $table->timestamp('uploaded_at')->nullable();

            $table->unsignedInteger('imageable_id');
            $table->string('imageable_type', 190);
            $table->index(['imageable_id', 'imageable_type']);
        });
    }
}
