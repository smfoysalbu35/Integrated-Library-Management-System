<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Transaction::class, function (Faker $faker) {
    $patron = factory(App\Models\Patron::class)->create();
    $user = factory(App\Models\User::class)->create();

    return [
        'patron_id' => $patron->id,
        'user_id' => $user->id,
        'transaction_date' => NOW(),
        'transaction_time' => NOW(),
        'total_penalty' => $faker->randomNumber(2),
        'payment' => $faker->randomNumber(2),
        'change' => $faker->randomNumber(2),
    ];
});
