<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Book::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'call_number' => $faker->isbn10,
        'isbn' => $faker->isbn10,

        'edition' => $faker->word,
        'volume' => $faker->randomNumber(3),

        'publisher' => $faker->company,
        'place_publication' => $faker->city,

        'copy_right' => $faker->year,
        'copy' => $faker->randomDigitNot(0),
    ];
});
