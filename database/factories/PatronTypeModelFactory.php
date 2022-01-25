<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\PatronType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'fines' => $faker->randomDigitNot(0),
        'no_of_borrow_allowed' => $faker->randomDigitNot(0),
        'no_of_day_borrow_allowed' => $faker->randomDigitNot(0),
        'no_of_reserve_allowed' => $faker->randomDigitNot(0),
        'no_of_day_reserve_allowed' => $faker->randomDigitNot(0),
    ];
});
