<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Penalty::class, function (Faker $faker) {
    $returnBook = factory(App\Models\ReturnBook::class)->create();
    $overdue = $faker->randomDigitNot(0);

    return [
        'return_book_id' => $returnBook->id,
        'patron_id' => $returnBook->patron->id,
        'accession_id' => $returnBook->accession->id,
        'penalty_due_date' => NOW(),
        'amount' => $returnBook->patron->patron_type->fines * $overdue,
        'overdue' => $overdue,
        'status' => 1,
    ];
});
