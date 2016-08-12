<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */

use App\Exams\Exam;
use App\Exams\Option;
use App\Exams\Paper;
use App\Exams\Question;
use Faker\Factory as Faker;

$factory->define(Exam::class, function () {
    $faker = Faker::create('zh_TW');

    return [
        'category_id' => random_category('exam.category'),
        'name'        => $faker->name,
        'enable'      => $faker->boolean(),
    ];
});

$factory->define(Question::class, function () {
    $faker = Faker::create('zh_TW');

    static $exams = null;

    if (is_null($exams)) {
        $exams = Exam::all(['id'])->pluck('id')->toArray();
    }

    return [
        'uuid'          => $faker->uuid,
        'exam_id'       => $faker->randomElement($exams),
        'content'       => $faker->realText(120),
        'multiple'      => $faker->boolean(),
        'difficulty_id' => random_category('exam.difficulty'),
        'explanation'   => $faker->boolean() ? $faker->realText(120) : null,
        'question_id'   => $faker->boolean() ? Question::whereNull('question_id')->orderByRand()->first()->getKey() : null,
    ];
});

$factory->define(Option::class, function () {
    $faker = Faker::create('zh_TW');

    static $questions = null;

    if (is_null($questions)) {
        $questions = Question::all(['id'])->pluck('id')->toArray();
    }

    return [
        'question_id' => $faker->randomElement($questions),
        'content'     => $faker->realText(120),
        'answer'      => $faker->boolean(),
    ];
});

$factory->define(Paper::class, function () {
    $faker = Faker::create('zh_TW');

    return [
        'name'      => $faker->name,
        'remark'    => $faker->boolean() ? $faker->realText(16) : null,
        'automatic' => $faker->boolean(),
    ];
});
