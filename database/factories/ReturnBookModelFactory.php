<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\ReturnBook::class, function (Faker $faker) {
    $borrow = factory(App\Models\Borrow::class)->create();

    return [
        'borrow_id' => $borrow->id,
        'patron_id' => $borrow->patron->id,
        'accession_id' => $borrow->accession->id,
        'return_date' => NOW(),
        'return_time' => NOW(),
    ];
});
