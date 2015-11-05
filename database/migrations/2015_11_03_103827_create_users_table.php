<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 32);
            $table->string('password', 100);
            $table->rememberToken();
            $table->string('name', 32);
            $table->string('email');
            $table->char('social_security_number', 10)->default('_infoexam_');
            $table->integer('gender_id')->unsigned()->nullable();
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('grade_id')->unsigned()->nullable();
            $table->char('class', 1)->default('A');
            $table->tinyInteger('test_count')->unsigned()->default(0);
            $table->tinyInteger('passed_score')->unsigned()->nullable();
            $table->timestamp('passed_at')->nullable();
            $table->timestamps();

            $table->unique('username');

            $table->index('remember_token');
            $table->index('name');
            $table->index('email');
            $table->index('social_security_number');
            $table->index('gender_id');
            $table->index('department_id');
            $table->index('grade_id');
            $table->index('class');
            $table->index('test_count');
            $table->index('passed_at');
            $table->index('updated_at');

            $table->foreign('gender_id')->references('id')->on('categories')
                ->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('categories')
                ->onUpdate('cascade');
            $table->foreign('grade_id')->references('id')->on('categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_gender_id_foreign');
            $table->dropForeign('users_department_id_foreign');
            $table->dropForeign('users_grade_id_foreign');
        });

        Schema::drop('users');
    }
}
