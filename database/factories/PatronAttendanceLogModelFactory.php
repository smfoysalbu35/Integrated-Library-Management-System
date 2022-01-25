<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\PatronAttendanceLog::class, function (Faker $faker) {
    $patron = factory(App\Models\Patron::class)->create();

    return [
        'patron_id' => $patron->id,
        'date_in' => NOW(),
        'time_in' => NOW(),
        'date_out' => NOW(),
        'time_out' => NOW(),
        'status' => 1,
    ];
});
