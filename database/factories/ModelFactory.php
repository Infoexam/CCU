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

$factory->define(App\Infoexam\User\User::class, function (Faker\Generator $faker) {
    $trueOrFalse = random_int(0, 1);

    return [
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'name' => $faker->name,
        'email' => $faker->email,
        'social_security_number' => str_random(10),
        'class' => $trueOrFalse ? 'A' : 'B',
        'test_count' => $faker->numberBetween(0, 100),
        'passed_score' => $trueOrFalse ? $faker->numberBetween(0, 100) : null,
        'passed_at' => $trueOrFalse ? $faker->dateTime : null
    ];
});
