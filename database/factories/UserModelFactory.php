<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'last_name' => $faker->lastName,
        'first_name' => $faker->firstName('male'),
        'middle_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => Carbon::now(),
        'password' => Hash::make(config('global.default_password')),
        'image' => $faker->imageUrl(640, 480),
        'user_type' => 1,
        'status' => 1,
        'remember_token' => Str::random(10),
    ];
});
