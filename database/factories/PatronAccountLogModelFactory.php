<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\PatronAccountLog::class, function (Faker $faker) {
    $patronAccount = factory(App\Models\PatronAccount::class)->create();

    return [
        'patron_account_id' => $patronAccount->id,
        'date_in' => NOW(),
        'time_in' => NOW(),
        'date_out' => NOW(),
        'time_out' => NOW(),
        'status' => 1,
    ];
});
