<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Borrow::class, function (Faker $faker) {
    $patron = factory(App\Models\Patron::class)->create();
    $accession = factory(App\Models\Accession::class)->create();

    return [
        'patron_id' => $patron->id,
        'accession_id' => $accession->id,
        'borrow_date' => NOW(),
        'borrow_time' => NOW(),
        'status' => 1,
    ];
});
