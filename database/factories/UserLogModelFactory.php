<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\UserLog::class, function (Faker $faker) {
    $user = factory(App\Models\User::class)->create();

    return [
        'user_id' => $user->id,
        'date_in' => NOW(),
        'time_in' => NOW(),
        'date_out' => NOW(),
        'time_out' => NOW(),
        'status' => 1,
    ];
});
