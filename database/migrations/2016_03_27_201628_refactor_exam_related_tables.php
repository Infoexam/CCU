<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorExamRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_applies');
        Schema::dropIfExists('exam_lists');
        Schema::dropIfExists('exam_paper_exam_question');
        Schema::dropIfExists('exam_papers');
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_explanations');
        Schema::dropIfExists('exam_options');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_sets');

        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');

            // 關聯鍵.
            $table->unsignedInteger('category_id')->index();

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');

            // 題庫資訊.
            $table->string('name', 16);
            $table->boolean('enable')->default(false)->index();

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();

            $table->index('created_at');
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');

            // 關聯鍵.
            $table->unsignedInteger('exam_id')->index();

            $table->foreign('exam_id')->references('id')->on('exams')->onUpdate('cascade');

            // 題目資訊.
            $table->string('content', 1000)->nullable();
            $table->boolean('multiple')->default(false)->index();
            $table->unsignedInteger('difficulty_id')->index();
            $table->string('explanation', 1000)->nullable();
            $table->unsignedInteger('question_id')->nullable()->index();

            $table->foreign('difficulty_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');

            // 關聯鍵.
            $table->unsignedInteger('question_id')->index();

            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');

            // 選項資訊.
            $table->string('content', 1000)->nullable();

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('option_id');

            $table->primary(['question_id', 'option_id']);

            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onUpdate('cascade');
        });

        Schema::create('papers', function (Blueprint $table) {
            $table->increments('id');

            // 試卷資料.
            $table->string('name', 16);
            $table->string('remark', 190)->nullable();
            $table->boolean('automatic')->default(false)->index();

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();

            $table->index('created_at');
        });

        Schema::create('paper_question', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('paper_id');
            $table->unsignedInteger('question_id');

            $table->unique(['paper_id', 'question_id']);

            $table->foreign('paper_id')->references('id')->on('papers')->onUpdate('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade');
        });

        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');

            // 測驗時間與地點資訊.
            $table->char('code', 15)->unique();
            $table->timestamp('began_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->unsignedTinyInteger('duration')->default(90);
            $table->timestamp('started_at')->nullable();
            $table->string('room', 8);

            // 測驗內容與報名資訊.
            $table->boolean('applicable')->default(false)->index();
            $table->unsignedInteger('paper_id')->nullable()->index();
            $table->unsignedInteger('apply_type_id')->index();
            $table->unsignedInteger('subject_id')->index();

            $table->foreign('paper_id')->references('id')->on('papers')->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('categories')->onUpdate('cascade');

            // 測驗人數資訊.
            $table->unsignedTinyInteger('maximum_num');
            $table->unsignedTinyInteger('applied_num')->default(0);
            $table->unsignedTinyInteger('tested_num')->default(0);

            // 測驗題目備份.
            $table->string('log', 16000)->nullable();

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });

        Schema::create('applies', function (Blueprint $table) {
            $table->increments('id');

            // 關聯鍵.
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('listing_id')->index();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onUpdate('cascade');

            // 報名資訊.
            $table->char('type', 1)->default('S'); // S自行報名 / A管理員報名
            $table->timestamp('paid_at')->nullable();

            // Timestamps.
            $table->timestamps();
            $table->softDeletes()->index();
        });

        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');

            // 關聯鍵.
            $table->unsignedInteger('apply_id')->index();

            $table->foreign('apply_id')->references('id')->on('applies')->onUpdate('cascade');

            // 測驗資訊.
            $table->unsignedTinyInteger('duration');
            $table->boolean('re_sign_in')->default(false);

            // 測驗結果.
            $table->timestamp('signed_in_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 6, 3)->nullable();
            $table->string('log', 16000)->nullable();

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
        Schema::dropIfExists('results');
        Schema::dropIfExists('applies');
        Schema::dropIfExists('listings');
        Schema::dropIfExists('paper_question');
        Schema::dropIfExists('papers');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');

        Schema::create('exam_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->unsignedInteger('category_id');
            $table->boolean('enable')->default(false);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('category_id');
            $table->index('enable');
            $table->index('deleted_at');

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_set_id');
            $table->string('content', 1000);
            $table->unsignedInteger('difficulty_id');
            $table->boolean('multiple');
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('exam_set_id');
            $table->index('difficulty_id');
            $table->index('multiple');
            $table->index('deleted_at');

            $table->foreign('exam_set_id')->references('id')->on('exam_sets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('difficulty_id')->references('id')->on('categories')->onUpdate('cascade');
        });

        Schema::create('exam_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_question_id');
            $table->string('content', 1000);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('exam_question_id');
            $table->index('deleted_at');

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('exam_explanations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_question_id');
            $table->string('content', 1000);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('exam_question_id');
            $table->index('deleted_at');

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('exam_answers', function (Blueprint $table) {
            $table->unsignedInteger('exam_question_id');
            $table->unsignedInteger('exam_option_id');

            $table->primary(['exam_question_id', 'exam_option_id']);

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('exam_option_id')->references('id')->on('exam_options')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('papers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('remark', 190)->nullable();
            $table->boolean('automatic')->default(false);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('automatic');
            $table->index('deleted_at');
        });

        Schema::create('paper_exam_question', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('paper_id');
            $table->unsignedInteger('exam_question_id');

            $table->unique(['paper_id', 'exam_question_id']);

            $table->foreign('exam_question_id')->references('id')->on('exam_questions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('paper_id')->references('id')->on('papers')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::rename('papers', 'exam_papers');

        Schema::table('paper_exam_question', function (Blueprint $table) {
            $table->dropForeign('paper_exam_question_paper_id_foreign');
        });

        Schema::rename('paper_exam_question', 'exam_paper_exam_question');

        Schema::table('exam_paper_exam_question', function (Blueprint $table) {
            $table->renameColumn('paper_id', 'exam_paper_id');

            $table->foreign('exam_paper_id')->references('id')->on('exam_papers')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('exam_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 13);
            $table->timestamp('began_at');
            $table->unsignedTinyInteger('duration');
            $table->string('room', 8);
            $table->unsignedInteger('paper_id')->nullable();
            $table->unsignedInteger('apply_type_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedTinyInteger('std_maximum_num');
            $table->unsignedTinyInteger('std_apply_num')->default(0);
            $table->unsignedTinyInteger('std_test_num')->default(0);
            $table->boolean('allow_apply')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unique('code');

            $table->index('began_at');
            $table->index('room');
            $table->index('paper_id');
            $table->index('apply_type_id');
            $table->index('subject_id');
            $table->index('created_at');
            $table->index('deleted_at');

            $table->foreign('paper_id')->references('id')->on('exam_papers')->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('categories')->onUpdate('cascade');
        });

        Schema::create('exam_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('exam_list_id');
            $table->unsignedInteger('apply_type_id');
            $table->timestamp('paid_at')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('exam_list_id');
            $table->index('apply_type_id');
            $table->index('created_at');
            $table->index('deleted_at');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('exam_list_id')->references('id')->on('exam_lists')->onUpdate('cascade');
            $table->foreign('apply_type_id')->references('id')->on('categories')->onUpdate('cascade');
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_apply_id');
            $table->decimal('score', 6, 3)->nullable();
            $table->string('log', 32768)->nullable();
            $table->boolean('allow_re_sign_in')->default(false);
            $table->timestamp('signed_in_at');
            $table->timestamp('submitted_at')->nullable();
            $table->nullableTimestamps();

            $table->index('score');

            $table->foreign('exam_apply_id')->references('id')->on('exam_applies')->onUpdate('cascade');
        });
    }
}
