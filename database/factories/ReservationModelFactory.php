<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Reservation::class, function (Faker $faker) {
    $patron = factory(App\Models\Patron::class)->create();
    $accession = factory(App\Models\Accession::class)->create();

    return [
        'patron_id' => $patron->id,
        'accession_id' => $accession->id,
        'reservation_date' => Carbon::now(),
        'reservation_time' => Carbon::now(),
        'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
    ];
});
