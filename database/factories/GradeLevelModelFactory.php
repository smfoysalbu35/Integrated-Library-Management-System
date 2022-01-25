<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\GradeLevel::class, function (Faker $faker) {
    return [
        'grade_level' => $faker->unique()->randomNumber(9),
    ];
});
