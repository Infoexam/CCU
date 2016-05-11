<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */

function randomCategory($category) {
    return \App\Categories\Category::getCategories($category)->random()->getAttribute('id');
}

git 
/**
 * users and certificates table
 */
$factory->define(App\Accounts\User::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'role' => $faker->randomElement(['admin', 'manager', 'student']),
        'name' => $faker->name,
        'email' => $faker->email,
        'gender' => $faker->randomElement(['M', 'F']),
        'department_id' => randomCategory('user.department'),
        'grade_id' => randomCategory('user.grade'),
        'class' => $faker->randomElement(['A', 'B']),
        'test_count' => $faker->numberBetween(0, 100),
    ];
});

$factory->defineAs(App\Accounts\User::class, 'passed', function () use ($factory) {
    $faker = Faker\Factory::create('zh_TW');

    return array_merge($factory->raw(App\Accounts\User::class), [
        'passed_score' => $faker->numberBetween(0, 100),
        'passed_at' => $faker->dateTime
    ]);
});

$factory->define(App\Accounts\Certificate::class, function () {
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
$factory->define(\App\Exams\Exam::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'name' => $faker->name,
        'category_id' => randomCategory('exam.category'),
        'enable' => $faker->boolean(),
    ];
});

$factory->define(\App\Exams\Question::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'content' => $faker->realText(120),
        'difficulty_id' => randomCategory('exam.difficulty'),
        'multiple' => $faker->boolean(),
    ];
});

$factory->define(\App\Exams\Option::class, function () {
    $faker = Faker\Factory::create('zh_TW');
    return [
        'content' => $faker->realText(120),
    ];
});

$factory->define(\App\Exams\Listing::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'code' => $faker->dateTime->format('YmdH') . $faker->numberBetween(100, 999),
        'began_at' => $faker->dateTime,
        'duration' => $faker->numberBetween(30, 90),
        'room' => $faker->numberBetween(100, 999),
        'paper_id' => \App\Exams\Paper::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.apply'),
        'subject_id' => randomCategory('exam.subject'),
        'std_maximum_num' => $faker->numberBetween(10, 50),
    ];
});

$factory->define(\App\Exams\Apply::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'user_id' => \App\Accounts\User::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.applied'),
        'paid_at' => $faker->boolean() ? $faker->dateTime : null,
    ];
});

$factory->define(\App\Exams\Result::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'score' => \App\Accounts\User::all()->random()->getAttribute('id'),
        'signed_in_at' => $faker->dateTime,
    ];
});


/**
 * papers table
 */
$factory->define(\App\Exams\Paper::class, function () {
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
$factory->define(\App\Websites\Announcement::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'heading' => $faker->realText(16) . str_random(4),
        'link' => $faker->boolean() ? $faker->url : null,
        'content' => $faker->realText(120),
    ];
});

$factory->define(\App\Websites\Faq::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'question' => $faker->realText(32),
        'answer' => $faker->realText(64),
    ];
});
