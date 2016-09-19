<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDeletedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('options', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('applies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->softDeletes()->index();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->softDeletes()->index();
        });

        Schema::table('options', function (Blueprint $table) {
            $table->softDeletes()->index();
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->softDeletes()->index();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->softDeletes()->index();
        });

        Schema::table('applies', function (Blueprint $table) {
            $table->softDeletes()->index();
        });
    }
}
