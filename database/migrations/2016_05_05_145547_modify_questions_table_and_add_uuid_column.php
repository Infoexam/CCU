<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyQuestionsTableAndAddUuidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('uuid', 36)->after('id')->unique();
            $table->string('content', 5000)->change();
            $table->string('explanation', 5000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->string('content', 1000)->change();
            $table->string('explanation', 1000)->change();
        });
    }
}
