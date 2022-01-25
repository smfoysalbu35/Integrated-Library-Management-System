<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\BookAuthor::class, function (Faker $faker) {
    $author = factory(App\Models\Author::class)->create();
    $book = factory(App\Models\Book::class)->create();

    return [
        'author_id' => $author->id,
        'book_id' => $book->id,
    ];
});
