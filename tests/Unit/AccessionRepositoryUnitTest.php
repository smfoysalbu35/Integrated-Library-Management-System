<?php

namespace Tests\Unit;

use App\Models\Accession;
use App\Models\Book;
use App\Models\Location;
use App\Repositories\Accession\AccessionRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessionRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Accession
    public function test_it_can_get_all_the_accessions()
    {
        factory(Accession::class, 3)->create();

        $repository = new AccessionRepository(new Accession);
        $accessions = $repository->get();

        $this->assertInstanceOf(Collection::class, $accessions);
    }

    //Paginate Accession
    public function test_it_can_paginate_the_accessions()
    {
        factory(Accession::class, 3)->create();

        $repository = new AccessionRepository(new Accession);
        $accessions = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $accessions);
    }

    //Search Accession
    public function test_it_can_search_the_accessions()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $accessions = $repository->search($accession->accession_no);

        $this->assertInstanceOf(Collection::class, $accessions);
    }

    //Get Accession by field name
    public function test_it_can_get_the_accessions_by_field_name()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $accessions = $repository->getBy('accession_no', $accession->accession_no);

        $this->assertInstanceOf(Collection::class, $accessions);
    }

    //Accession Data
    public function accession()
    {
        $book = factory(Book::class)->create();
        $location = factory(Location::class)->create();

        return [
            'accession_no' => $this->faker->unique()->isbn10,
            'book_id' => $book->id,
            'location_id' => $location->id,
            'acquired_date' => $this->faker->date,
            'donnor_name' => $this->faker->name,
            'price' => $this->faker->randomNumber(3),
            'status' => 1,
        ];
    }

    //Create Accession
    public function test_it_can_create_the_accession()
    {
        $repository = new AccessionRepository(new Accession);
        $accession = $repository->create($this->accession());

        $this->assertInstanceOf(Accession::class, $accession);
    }

    public function test_it_throws_errors_when_creating_the_accession()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->create([]);
    }

    //Find Accession
    public function test_it_can_find_the_accession()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $found = $repository->find($accession->id);

        $this->assertInstanceOf(Accession::class, $found);
        $this->assertEquals($accession->accession_no, $found->accession_no);
    }

    public function test_it_throws_errors_when_finding_the_accession()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Accession by field name
    public function test_it_can_find_the_accession_by_field_name()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $found = $repository->findBy('accession_no', $accession->accession_no);

        $this->assertInstanceOf(Accession::class, $found);
        $this->assertEquals($accession->accession_no, $found->accession_no);
    }

    public function test_it_throws_errors_when_finding_the_accession_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Accession
    public function test_it_can_update_the_accession()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $updated = $repository->update($data = $this->accession(), $accession->id);

        $this->assertInstanceOf(Accession::class, $updated);
        $this->assertEquals($updated->accession_no, $data['accession_no']);
    }

    public function test_it_throws_errors_when_updating_the_accession()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Accession
    public function test_it_can_delete_the_accession()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $deleted = $repository->delete($accession->id);

        $this->assertInstanceOf(Accession::class, $deleted);
        $this->assertEquals($accession->accession_no, $deleted->accession_no);
    }

    public function test_it_throws_errors_when_deleting_the_accession()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->delete($this->faker->randomNumber(9));
    }

    //Count Accession
    public function test_it_can_count_the_accession()
    {
        $repository = new AccessionRepository(new Accession);
        $count = $repository->count();

        $this->assertIsInt($count);
    }

    //Count Accession by field name
    public function test_it_can_count_the_accession_by_field_name()
    {
        $accession = factory(Accession::class)->create();

        $repository = new AccessionRepository(new Accession);
        $count = $repository->countBy('accession_no', $accession->accession_no);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_accession_by_field_name()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new AccessionRepository(new Accession);
        $repository->countBy();
    }

    //Get Acquisition Report
    public function test_it_can_list_the_acquisition_report()
    {
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new AccessionRepository(new Accession);
        $accessions = $repository->getAcquisitionReport($data);

        $this->assertInstanceOf(Collection::class, $accessions);
    }

    public function test_it_throws_errors_when_listing_the_acquisition_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new AccessionRepository(new Accession);
        $repository->getAcquisitionReport([]);
    }
}
