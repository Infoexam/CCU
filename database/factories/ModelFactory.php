<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Infoexam\General\Category;

/** @var $factory \Illuminate\Database\Eloquent\Factory */

/**
 * users and certificates table
 */
$factory->define(App\Infoexam\User\User::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'name' => $faker->name,
        'email' => $faker->email,
        'social_security_number' => str_random(10),
        'gender_id' => Category::getCategories('user.gender')->random()->getAttribute('id'),
        'department_id' => Category::getCategories('user.department')->random()->getAttribute('id'),
        'grade_id' => Category::getCategories('user.grade')->random()->getAttribute('id'),
        'class' => mt_rand(0, 1) ? 'A' : 'B',
        'test_count' => $faker->numberBetween(0, 100),
        'passed_score' => null,
        'passed_at' => null,
    ];
});

$factory->defineAs(App\Infoexam\User\User::class, 'passed', function () use ($factory) {
    $faker = Faker\Factory::create('zh_TW');

    return array_merge($factory->raw(App\Infoexam\User\User::class), [
        'passed_score' => $faker->numberBetween(0, 100),
        'passed_at' => $faker->dateTime
    ]);
});

$factory->define(App\Infoexam\User\Certificate::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'category_id' => Category::getCategories('exam.category')->random()->getAttribute('id'),
        'score' => mt_rand(0, 100),
    ];
});

/**
 * exam_sets, exam_questions, exam_options, exam_explanations,
 * exam_lists, exam_applies and exam_results tables
 */
$factory->define(\App\Infoexam\Exam\Set::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'name' => $faker->name,
        'category_id' => Category::getCategories('exam.category')->random()->getAttribute('id'),
        'enable' => mt_rand(0, 1),
    ];
});

$factory->define(\App\Infoexam\Exam\Question::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'content' => $faker->realText(120),
        'difficulty_id' => Category::getCategories('exam.difficulty')->random()->getAttribute('id'),
        'multiple' => mt_rand(0, 1),
    ];
});

$factory->define(\App\Infoexam\Exam\Option::class, function () {
    $faker = Faker\Factory::create('zh_TW');
    return [
        'content' => $faker->realText(120),
    ];
});

$factory->define(\App\Infoexam\Exam\Explanation::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'content' => $faker->realText(120),
    ];
});

$factory->define(\App\Infoexam\Exam\Lists::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'code' => str_random(13),
        'began_at' => $faker->dateTime,
        'duration' => mt_rand(30, 90),
        'room' => mt_rand(100, 999),
        'paper_id' => \App\Infoexam\Exam\Paper::all()->random()->getAttribute('id'),
        'apply_type_id' => Category::getCategories('exam.apply')->random()->getAttribute('id'),
        'subject_id' => Category::getCategories('exam.subject')->random()->getAttribute('id'),
        'std_maximum_num' => mt_rand(10, 50),
    ];
});

$factory->define(\App\Infoexam\Exam\Apply::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'user_id' => \App\Infoexam\User\User::all()->random()->getAttribute('id'),
        'apply_type_id' => Category::getCategories('exam.applied')->random()->getAttribute('id'),
        'paid_at' => mt_rand(0, 1) ? $faker->dateTime : null,
    ];
});

$factory->define(\App\Infoexam\Exam\Result::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'score' => \App\Infoexam\User\User::all()->random()->getAttribute('id'),
        'signed_in_at' => $faker->dateTime,
    ];
});

/**
 * papers table
 */
$factory->define(\App\Infoexam\Exam\Paper::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'name' => $faker->name,
        'remark' => $faker->realText(16),
        'automatic' => mt_rand(0, 1),
    ];
});

/**
 * announcements and faqs table
 */
$factory->define(\App\Infoexam\Website\Announcement::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'heading' => $faker->realText(16) . str_random(4),
        'link' => mt_rand(0, 1) ? $faker->url : null,
        'content' => $faker->realText(120),
    ];
});

$factory->define(\App\Infoexam\Website\Faq::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'question' => $faker->realText(32),
        'answer' => $faker->realText(64),
    ];
});
