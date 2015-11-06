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

/**
 * users and certificates table
 */
$factory->define(App\Infoexam\User\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'name' => $faker->name,
        'email' => $faker->email,
        'social_security_number' => str_random(10),
        'gender_id' => Category::where('category', '=', 'user.gender')->get()->random()->getAttribute('id'),
        'department_id' => Category::where('category', '=', 'user.department')->get()->random()->getAttribute('id'),
        'grade_id' => Category::where('category', '=', 'user.grade')->get()->random()->getAttribute('id'),
        'class' => random_int(0, 1) ? 'A' : 'B',
        'test_count' => $faker->numberBetween(0, 100),
        'passed_score' => null,
        'passed_at' => null,
    ];
});

$factory->defineAs(App\Infoexam\User\User::class, 'passed', function (Faker\Generator $faker) use ($factory) {
    return array_merge($factory->raw(App\Infoexam\User\User::class), [
        'passed_score' => $faker->numberBetween(0, 100),
        'passed_at' => $faker->dateTime
    ]);
});

$factory->define(App\Infoexam\User\Certificate::class, function (Faker\Generator $faker) {
    return [
        'category_id' => Category::where('category', '=', 'exam.category')->get()->random()->getAttribute('id'),
        'score' => random_int(0, 100),
    ];
});

/**
 * exam_sets, exam_questions, exam_options, exam_explanations and exam_lists table
 */
$factory->define(\App\Infoexam\Exam\Set::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'category_id' => Category::where('category', '=', 'exam.category')->get()->random()->getAttribute('id'),
        'enable' => random_int(0, 1),
    ];
});

$factory->define(\App\Infoexam\Exam\Question::class, function (\Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
        'difficulty_id' => Category::where('category', '=', 'exam.difficulty')->get()->random()->getAttribute('id'),
        'multiple' => random_int(0, 1),
    ];
});

$factory->define(\App\Infoexam\Exam\Option::class, function (\Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});

$factory->define(\App\Infoexam\Exam\Explanation::class, function (\Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});

$factory->define(\App\Infoexam\Exam\Lists::class, function (\Faker\Generator $faker) {
    return [
        'code' => str_random(13),
        'began_at' => $faker->dateTime,
        'duration' => random_int(30, 90),
        'room' => random_int(100, 999),
        'paper_id' => \App\Infoexam\Paper\Paper::all()->random()->getAttribute('id'),
        'apply_type_id' => 1,
        'subject_id' => 2,
        'std_maximum_num' => random_int(10, 50),
    ];
});

$factory->define(\App\Infoexam\Exam\Apply::class, function (\Faker\Generator $faker) {
    return [
        'user_id' => \App\Infoexam\User\User::all()->random()->getAttribute('id'),
        'apply_type_id' => 1,
        'paid_at' => random_int(0, 1) ? $faker->dateTime : null,
    ];
});

$factory->define(\App\Infoexam\Exam\Result::class, function (\Faker\Generator $faker) {
    return [
        'score' => \App\Infoexam\User\User::all()->random()->getAttribute('id'),
        'signed_in_at' => $faker->dateTime,
    ];
});

/**
 * papers table
 */
$factory->define(\App\Infoexam\Paper\Paper::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'remark' => $faker->sentence,
        'automatic' => random_int(0, 1),
    ];
});

/**
 * announcements and faqs table
 */
$factory->define(\App\Infoexam\Website\Announcement::class, function (\Faker\Generator $faker) {
    return [
        'heading' => $faker->sentence . str_random(4),
        'link' => random_int(0, 1) ? $faker->url : null,
        'content' => $faker->paragraph,
    ];
});

$factory->define(\App\Infoexam\Website\Faq::class, function (\Faker\Generator $faker) {
    return [
        'question' => $faker->sentence,
        'answer' => $faker->sentence,
    ];
});
