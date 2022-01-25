<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\PatronAccount::class, function (Faker $faker) {
    $patron = factory(App\Models\Patron::class)->create();

    return [
        'patron_id' => $patron->id,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => Carbon::now(),
        'password' => Hash::make(config('global.default_password')),
        'status' => 1,
        'remember_token' => Str::random(10),
    ];
});
