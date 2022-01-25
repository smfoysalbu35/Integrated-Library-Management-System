<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Section::class, function (Faker $faker) {
    $gradeLevel = factory(App\Models\GradeLevel::class)->create();

    return [
        'name' => $faker->unique()->name,
        'grade_level_id' => $gradeLevel->id,
    ];
});
