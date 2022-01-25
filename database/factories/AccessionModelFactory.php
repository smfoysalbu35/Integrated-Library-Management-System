<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Accession::class, function (Faker $faker) {
    $book = factory(App\Models\Book::class)->create();
    $location = factory(App\Models\Location::class)->create();

    return [
        'accession_no' => $faker->unique()->isbn10,
        'book_id' => $book->id,
        'location_id' => $location->id,
        'acquired_date' => $faker->date,
        'donnor_name' => $faker->name,
        'price' => $faker->randomNumber(3),
        'status' => 1,
    ];
});
