<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Patron::class, function (Faker $faker) {
    $patronType = factory(App\Models\PatronType::class)->create();
    $section = factory(App\Models\Section::class)->create();

    return [
        'patron_no' => $faker->unique()->isbn10,
        'last_name' => $faker->lastName,
        'first_name' => $faker->firstName('male'),
        'middle_name' => $faker->lastName,

        'contact_no' => $faker->phoneNumber,
        'image' => $faker->imageUrl(640, 480),

        'house_no' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'barangay' => $faker->secondaryAddress,
        'municipality' => $faker->city,
        'province' => $faker->state,

        'patron_type_id' => $patronType->id,
        'section_id' => $section->id,
    ];
});
