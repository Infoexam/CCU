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


            // 使用者帳號資訊.
            $table->string('username', 12)->unique();
            $table->string('password', 100);
            $table->rememberToken()->index();
            $table->string('role', 12);


            // 使用者個人資訊.
            $table->string('name', 12)->index();
            $table->string('email', 48);
            $table->char('gender', 1);
            $table->unsignedInteger('department_id')->index();
            $table->unsignedInteger('grade_id')->index();
            $table->char('class', 1)->default('A');

            $table->foreign('department_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('grade_id')->references('id')->on('categories')->onUpdate('cascade');


            // 使用者測驗資訊.
            $table->unsignedTinyInteger('test_count')->default(0);
            $table->decimal('passed_score', 6, 3)->nullable();
            $table->timestamp('passed_at')->nullable()->index();


            // Timestamps.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
