<?php

/** @var Illuminate\Database\Eloquent\Factory $factory */
use App\Accounts\Certificate;
use App\Accounts\Receipt;
use App\Accounts\User;
use Faker\Factory as Faker;

$factory->define(User::class, function () {
    $faker = Faker::create('zh_TW');

    return [
        'username'      => $faker->userName,
        'password'      => bcrypt(str_random()),
        'role'          => $faker->randomElement(['under', 'post', 'doctoral']),
        'name'          => $faker->name,
        'email'         => $faker->email,
        'gender'        => $faker->boolean() ? 'M' : 'F',
        'department_id' => random_category('user.department'),
        'grade_id'      => random_category('user.grade'),
        'class'         => $faker->boolean() ? 'A' : 'B',
        'test_count'    => $faker->numberBetween(0, 10),
    ];
});

$factory->defineAs(User::class, 'passed', function () use ($factory) {
    $faker = Faker::create('zh_TW');

    return array_merge($factory->raw(User::class), [
            'passed_score' => $faker->randomFloat(null, 0, 100),
            'passed_at'    => $faker->dateTime,
        ]
    );
});

$factory->define(Certificate::class, function () {
    $faker = Faker::create('zh_TW');

    static $users = null;

    if (is_null($users)) {
        $users = User::all(['id'])->pluck('id')->toArray();
    }

    return [
        'user_id'     => $faker->randomElement($users),
        'category_id' => random_category('exam.category'),
        'score'       => $faker->boolean() ? $faker->randomFloat(null, 0, 100) : null,
        'free'        => $faker->numberBetween(0, 5),
    ];
});

$factory->define(Receipt::class, function () {
    $faker = Faker::create('zh_TW');

    static $users = null;

    if (is_null($users)) {
        $users = User::all(['id'])->pluck('id')->toArray();
    }

    return [
        'receipt_no'   => $faker->toUpper($faker->bothify('??######')),
        'receipt_date' => $faker->date('Ymd'),
        'user_id'      => $faker->randomElement($users),
        'category_id'  => random_category('exam.category'),
    ];
});
