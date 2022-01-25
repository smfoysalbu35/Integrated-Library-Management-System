<?php

namespace Tests\Unit;

use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Repositories\Borrow\BorrowRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Borrow
    public function test_it_can_get_all_the_borrows()
    {
        factory(Borrow::class, 3)->create();

        $repository = new BorrowRepository(new Borrow);
        $borrows = $repository->get();

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    //Paginate Borrow
    public function test_it_can_paginate_the_borrows()
    {
        factory(Borrow::class, 3)->create();

        $repository = new BorrowRepository(new Borrow);
        $borrows = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $borrows);
    }

    //Get Borrow by field name
    public function test_it_can_get_the_borrows_by_field_name()
    {
        $borrow = factory(Borrow::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $borrows = $repository->getBy('id', $borrow->id);

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    //Get Borrow by patron id
    public function test_it_can_get_the_borrows_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $borrows = $repository->getBorrowByPatronId($patron->id);

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    public function test_it_throws_errors_when_getting_the_borrows_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->getBorrowByPatronId();
    }

    //Get Latest Borrow Transaction
    public function test_it_can_get_the_latest_borrow_transaction()
    {
        factory(Borrow::class, 3)->create();

        $repository = new BorrowRepository(new Borrow);
        $borrows = $repository->getLatestBorrowTransaction();

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    //Borrow Data
    public function borrow()
    {
        $accession = factory(Accession::class)->create();
        $patron = factory(Patron::class)->create();

        return [
            'accession_id' => $accession->id,
            'patron_id' => $patron->id,
            'borrow_date' => NOW(),
            'borrow_time' => NOW(),
            'status' => 1,
        ];
    }

    //Create Borrow
    public function test_it_can_create_the_borrow()
    {
        $repository = new BorrowRepository(new Borrow);
        $borrow = $repository->create($this->borrow());

        $this->assertInstanceOf(Borrow::class, $borrow);
    }

    public function test_it_throws_errors_when_creating_the_borrow()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->create([]);
    }

    //Find Borrow
    public function test_it_can_find_the_borrow()
    {
        $borrow = factory(Borrow::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $found = $repository->find($borrow->id);

        $this->assertInstanceOf(Borrow::class, $found);
        $this->assertEquals($borrow->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_borrow()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Borrow by field name
    public function test_it_can_find_the_borrow_by_field_name()
    {
        $borrow = factory(Borrow::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $found = $repository->findBy('id', $borrow->id);

        $this->assertInstanceOf(Borrow::class, $found);
        $this->assertEquals($borrow->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_borrow_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Find Patron Borrow Accession
    public function test_it_can_find_the_patron_borrow_accession()
    {
        $borrow = factory(Borrow::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $found = $repository->findPatronBorrowAccession($borrow->accession->id, $borrow->patron->id);

        $this->assertInstanceOf(Borrow::class, $found);
        $this->assertEquals($borrow->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_patron_borrow_accession()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->findPatronBorrowAccession($this->faker->randomNumber(9), $this->faker->randomNumber(9));
    }

    //Update Borrow
    public function test_it_can_update_the_borrow()
    {
        $borrow = factory(Borrow::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $updated = $repository->update($data = $this->borrow(), $borrow->id);

        $this->assertInstanceOf(Borrow::class, $updated);
        $this->assertEquals($updated->accession_id, $data['accession_id']);
        $this->assertEquals($updated->patron_id, $data['patron_id']);
    }

    public function test_it_throws_errors_when_updating_the_borrow()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Get Borrow Count by patron id
    public function test_it_can_count_the_borrow_by_patron_id()
    {
        $patron = factory(Patron::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $count = $repository->getBorrowCountByPatronId($patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_borrow_by_patron_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->getBorrowCountByPatronId();
    }

    //Get Borrow Count by accession id
    public function test_it_can_count_the_borrow_by_accession_id()
    {
        $accession = factory(Accession::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $count = $repository->getBorrowCountByAccessionId($accession->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_borrow_by_accession_id()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->getBorrowCountByAccessionId();
    }

    //Check If Patron Borrow Accession
    public function test_it_can_check_if_the_patron_borrow_accession()
    {
        $accession = factory(Accession::class)->create();
        $patron = factory(Patron::class)->create();

        $repository = new BorrowRepository(new Borrow);
        $count = $repository->checkIfPatronBorrowAccession($accession->id, $patron->id);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_checking_if_the_patron_borrow_accession()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new BorrowRepository(new Borrow);
        $repository->checkIfPatronBorrowAccession();
    }
}
