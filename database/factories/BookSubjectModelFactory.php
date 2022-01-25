<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\BookSubject::class, function (Faker $faker) {
    $book = factory(App\Models\Book::class)->create();
    $subject = factory(App\Models\Subject::class)->create();

    return [
        'book_id' => $book->id,
        'subject_id' => $subject->id,
    ];
});
