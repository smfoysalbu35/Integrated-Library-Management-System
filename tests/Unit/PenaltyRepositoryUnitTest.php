<?php

namespace Tests\Unit;

use App\Models\Patron;
use App\Models\Penalty;
use App\Models\ReturnBook;
use App\Repositories\Penalty\PenaltyRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PenaltyRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Penalty
    public function test_it_can_get_all_the_penalties()
    {
        factory(Penalty::class, 3)->create();

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->get();

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    //Paginate Penalty
    public function test_it_can_paginate_the_penalties()
    {
        factory(Penalty::class, 3)->create();

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $penalties);
    }

    //Get Penalty by field name
    public function test_it_can_get_the_penalties_by_field_name()
    {
        $penalty = factory(Penalty::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->getBy('id', $penalty->id);

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    //Get Penalty by patron id
    public function test_it_can_get_the_penalties_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->getPenaltyByPatronId($patron->id);

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    public function test_it_throws_errors_when_getting_the_penalties_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->getPenaltyByPatronId();
    }

    //Penalty Data
    public function penalty()
    {
        $returnBook = factory(ReturnBook::class)->create();
        $overdue = $this->faker->randomDigitNot(0);

        return [
            'return_book_id' => $returnBook->id,
            'patron_id' => $returnBook->patron->id,
            'accession_id' => $returnBook->accession->id,
            'penalty_due_date' => NOW(),
            'amount' => $returnBook->patron->patron_type->fines * $overdue,
            'overdue' => $overdue,
            'status' => 1,
        ];
    }

    //Create Penalty
    public function test_it_can_create_the_penalty()
    {
        $repository = new PenaltyRepository(new Penalty);
        $penalty = $repository->create($this->penalty());

        $this->assertInstanceOf(Penalty::class, $penalty);
    }

    public function test_it_throws_errors_when_creating_the_penalty()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->create([]);
    }

    //Find Penalty
    public function test_it_can_find_the_penalty()
    {
        $penalty = factory(Penalty::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $found = $repository->find($penalty->id);

        $this->assertInstanceOf(Penalty::class, $found);
        $this->assertEquals($penalty->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_penalty()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Penalty by field name
    public function test_it_can_find_the_penalty_by_field_name()
    {
        $penalty = factory(Penalty::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $found = $repository->findBy('id', $penalty->id);

        $this->assertInstanceOf(Penalty::class, $found);
        $this->assertEquals($penalty->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_penalty_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Find Total Penalty by patron id
    public function test_it_can_find_the_patron_total_penalty_by_patron_id()
    {
        $patron = factory(Patron::class)->create();
        $penalty = factory(Penalty::class)->create(['patron_id' => $patron->id]);

        $repository = new PenaltyRepository(new Penalty);
        $totalPenalty = $repository->findTotalPenaltyByPatronId($patron->id);

        $this->assertIsNumeric($totalPenalty);
    }

    public function test_it_throws_errors_when_finding_the_patron_total_penalty_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->findTotalPenaltyByPatronId();
    }

    //Update Penalty
    public function test_it_can_update_the_penalty()
    {
        $penalty = factory(Penalty::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $updated = $repository->update($data = $this->penalty(), $penalty->id);

        $this->assertInstanceOf(Penalty::class, $updated);
        $this->assertEquals($updated->return_book_id, $data['return_book_id']);
        $this->assertEquals($updated->patron_id, $data['patron_id']);
        $this->assertEquals($updated->accession_id, $data['accession_id']);
    }

    public function test_it_throws_errors_when_updating_the_penalty()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Get Penalty Count by patron id
    public function test_it_can_count_the_penalty_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PenaltyRepository(new Penalty);
        $count = $repository->getPenaltyCountByPatronId($patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_penalty_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->getPenaltyCountByPatronId();
    }

    //Get Paid Penalty Report
    public function test_it_can_list_the_paid_penalty_report()
    {
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->getPaidPenaltyReport($data);

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    public function test_it_throws_errors_when_listing_the_paid_penalty_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->getPaidPenaltyReport([]);
    }

    //Get Unpaid Penalty Report
    public function test_it_can_list_the_unpaid_penalty_report()
    {
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new PenaltyRepository(new Penalty);
        $penalties = $repository->getUnpaidPenaltyReport($data);

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    public function test_it_throws_errors_when_listing_the_unpaid_penalty_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new PenaltyRepository(new Penalty);
        $repository->getUnpaidPenaltyReport([]);
    }
}
