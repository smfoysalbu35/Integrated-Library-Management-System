<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Location::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'symbol' => $faker->word,
        'allowed' => $faker->word,
    ];
});
