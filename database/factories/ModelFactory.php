<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */
function randomCategory($category)
{
    return \Infoexam\Eloquent\Models\Category::getCategories($category)->random()->getAttribute('id');
}

/*
 * exam_sets, exam_questions, exam_options, exam_explanations,
 * exam_lists, exam_applies and exam_results tables
 */

$factory->define(\Infoexam\Eloquent\Models\Listing::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'code' => $faker->dateTime->format('YmdH').$faker->numberBetween(100, 999),
        'began_at' => $faker->dateTime,
        'duration' => $faker->numberBetween(30, 90),
        'room' => $faker->numberBetween(100, 999),
        'paper_id' => \Infoexam\Eloquent\Models\Paper::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.apply'),
        'subject_id' => randomCategory('exam.subject'),
        'std_maximum_num' => $faker->numberBetween(10, 50),
    ];
});

$factory->define(\Infoexam\Eloquent\Models\Apply::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'user_id' => \Infoexam\Eloquent\Models\User::all()->random()->getAttribute('id'),
        'apply_type_id' => randomCategory('exam.applied'),
        'paid_at' => $faker->boolean() ? $faker->dateTime : null,
    ];
});

$factory->define(\Infoexam\Eloquent\Models\Result::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'score' => \Infoexam\Eloquent\Models\User::all()->random()->getAttribute('id'),
        'signed_in_at' => $faker->dateTime,
    ];
});

/*
 * announcements and faqs table
 */
$factory->define(\App\Websites\Announcement::class, function () {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'heading' => $faker->realText(16).str_random(4),
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
