<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['grade_id']);
            $table->dropIndex(['email']);
            $table->dropIndex(['class']);
            $table->dropIndex(['test_count']);
            $table->dropColumn(['ssn', 'gender_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 12)->change();
            $table->string('name', 12)->change();
            $table->string('email', 48)->change();
            $table->unsignedInteger('department_id')->nullable(false)->change();
            $table->unsignedInteger('grade_id')->nullable(false)->change();
            $table->decimal('passed_score', 6, 3)->change();

            $table->string('role', 12)->after('remember_token');
            $table->char('gender', 1)->after('email');

            $table->foreign('department_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('grade_id')->references('id')->on('categories')->onUpdate('cascade');
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
            $table->dropColumn(['role', 'gender']);

            $table->string('username', 32)->change();
            $table->string('name', 32)->change();
            $table->string('email', 128)->index()->change();
            $table->unsignedInteger('department_id')->nullable()->change();
            $table->unsignedInteger('grade_id')->nullable()->change();
            $table->unsignedSmallInteger('passed_score')->change();

            $table->char('ssn', 10)->default('_infoexam_')->comment('身份證字號')->after('email');
            $table->unsignedInteger('gender_id')->nullable()->index()->after('ssn');

            $table->index('class');
            $table->index('test_count');

            $table->foreign('gender_id')->references('id')->on('categories')->onUpdate('cascade');
        });
    }
}
