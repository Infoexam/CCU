<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */

function randomCategory($category) {
    return \App\Infoexam\General\Category::getCategories($category)->random()->getAttribute('id');
}


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
        'ssn' => $faker->toUpper($faker->randomLetter) . $faker->numberBetween(100000000, 299999999),
        'gender_id' => randomCategory('user.gender'),
        'department_id' => randomCategory('user.department'),
        'grade_id' => randomCategory('user.grade'),
        'class' => $faker->boolean() ? 'A' : 'B',
        'test_count' => $faker->numberBetween(0, 100),
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
        'category_id' => randomCategory('exam.category'),
        'score' => $faker->numberBetween(0, 100),
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
        'category_id' => randomCategory('exam.category'),
        'enable' => $faker->boolean(),
    ];
});

$factory->define(\App\Infoexam\Exam\Question::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'content' => $faker->realText(120),
        'difficulty_id' => randomCategory('exam.difficulty'),
        'multiple' => $faker->boolean(),
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
        'code' => $faker->dateTime->format('YmdH') . $faker->numberBetween(100, 999),
        'began_at' => $faker->dateTime,
        'duration' => $faker->numberBetween(30, 90),
        'room' => $faker->numberBetween(100, 999),
        'paper_id' => \App\Infoexam\Exam\Paper::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.apply'),
        'subject_id' => randomCategory('exam.subject'),
        'std_maximum_num' => $faker->numberBetween(10, 50),
    ];
});

$factory->define(\App\Infoexam\Exam\Apply::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'user_id' => \App\Infoexam\User\User::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.applied'),
        'paid_at' => $faker->boolean() ? $faker->dateTime : null,
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
        'automatic' => $faker->boolean(),
    ];
});


/**
 * announcements and faqs table
 */
$factory->define(\App\Infoexam\Website\Announcement::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'heading' => $faker->realText(16) . str_random(4),
        'link' => $faker->boolean() ? $faker->url : null,
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
