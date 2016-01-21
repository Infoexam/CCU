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
            $table->string('email', 128);
            $table->char('social_security_number', 10)->default('_infoexam_')->comment('身份證字號');
            $table->unsignedInteger('gender_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('grade_id')->nullable();
            $table->char('class', 1)->default('A');
            $table->unsignedTinyInteger('test_count')->default(0);
            $table->unsignedTinyInteger('passed_score')->nullable();
            $table->timestamp('passed_at')->nullable();
            $table->timestamps();

            $table->unique('username');

            $table->index('remember_token');
            $table->index('name');
            $table->index('email');
            $table->index('gender_id');
            $table->index('department_id');
            $table->index('grade_id');
            $table->index('class');
            $table->index('test_count');
            $table->index('passed_at');

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
