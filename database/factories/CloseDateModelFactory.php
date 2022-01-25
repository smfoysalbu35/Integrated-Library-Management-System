<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\CloseDate::class, function (Faker $faker) {
    return [
        'close_date' => $faker->unique()->date,
        'description' => $faker->text(191),
    ];
});
