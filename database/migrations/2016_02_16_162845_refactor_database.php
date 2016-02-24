<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('configs');
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_applies');
        Schema::dropIfExists('exam_explanations');
        Schema::dropIfExists('exam_lists');
        Schema::dropIfExists('exam_options');
        Schema::dropIfExists('exam_paper_exam_question');
        Schema::dropIfExists('exam_papers');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_sets');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('images');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('users');
        Schema::dropIfExists('migrations');

        Artisan::call('migrate:install');

        Schema::table('migrations', function (Blueprint $table) {
            $table->string('migration', 190)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
