<?php

namespace Tests\Unit;

use App\Models\Patron;
use App\Models\PatronType;
use App\Models\Section;
use App\Repositories\Patron\PatronRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Patron
    public function test_it_can_get_all_the_patrons()
    {
        factory(Patron::class, 3)->create();

        $repository = new PatronRepository(new Patron);
        $patrons = $repository->get();

        $this->assertInstanceOf(Collection::class, $patrons);
    }

    //Paginate Patron
    public function test_it_can_paginate_the_patrons()
    {
        factory(Patron::class, 3)->create();

        $repository = new PatronRepository(new Patron);
        $patrons = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $patrons);
    }

    //Get Patron by field name
    public function test_it_can_get_the_patrons_by_field_name()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $patrons = $repository->getBy('patron_no', $patron->patron_no);

        $this->assertInstanceOf(Collection::class, $patrons);
    }

    //Create Patron No.
    public function test_it_can_create_the_patron_no()
    {
        $repository = new PatronRepository(new Patron);
        $patronNo = $repository->createPatronNo();

        $this->assertNotEmpty($patronNo);
        $this->assertIsString($patronNo);
    }

    //Patron Data
    protected function patron()
    {
        $patronType = factory(PatronType::class)->create();
        $section = factory(Section::class)->create();

        return new Request([
            'patron_no' => $this->faker->unique()->isbn10,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->lastName,

            'contact_no' => $this->faker->phoneNumber,
            'image' => $this->faker->imageUrl(640, 480),

            'house_no' => $this->faker->buildingNumber,
            'street' => $this->faker->streetName,
            'barangay' => $this->faker->secondaryAddress,
            'municipality' => $this->faker->city,
            'province' => $this->faker->state,

            'patron_type_id' => $patronType->id,
            'section_id' => $section->id,
        ]);
    }

    //Create Patron
    public function test_it_can_create_the_patron()
    {
        $repository = new PatronRepository(new Patron);
        $patron = $repository->create($this->patron());

        $this->assertInstanceOf(Patron::class, $patron);
    }

    public function test_it_throws_errors_when_creating_the_patron()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PatronRepository(new Patron);
        $repository->create(new Request([]));
    }

    //Find Patron
    public function test_it_can_find_the_patron()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $found = $repository->find($patron->id);

        $this->assertInstanceOf(Patron::class, $found);
        $this->assertEquals($patron->patron_no, $found->patron_no);
    }

    public function test_it_throws_errors_when_finding_the_patron()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronRepository(new Patron);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Patron by field name
    public function test_it_can_find_the_patron_by_field_name()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $found = $repository->findBy('patron_no', $patron->patron_no);

        $this->assertInstanceOf(Patron::class, $found);
        $this->assertEquals($patron->patron_no, $found->patron_no);
    }

    public function test_it_throws_errors_when_finding_the_patron_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronRepository(new Patron);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Patron
    public function test_it_can_update_the_patron()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $updated = $repository->update($data = $this->patron(), $patron->id);

        $this->assertInstanceOf(Patron::class, $updated);
        $this->assertEquals($updated->patron_no, $data['patron_no']);
    }

    public function test_it_throws_errors_when_updating_the_patron()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PatronRepository(new Patron);
        $repository->create(new Request([]), $this->faker->randomNumber(9));
    }

    //Delete Patron
    public function test_it_can_delete_the_patron()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $deleted = $repository->delete($patron->id);

        $this->assertInstanceOf(Patron::class, $deleted);
        $this->assertEquals($patron->patron_no, $deleted->patron_no);
    }

    public function test_it_throws_errors_when_deleting_the_patron()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronRepository(new Patron);
        $repository->delete($this->faker->randomNumber(9));
    }

    //Count Patron
    public function test_it_can_count_the_patron()
    {
        $repository = new PatronRepository(new Patron);
        $count = $repository->count();

        $this->assertIsInt($count);
    }

    //Count Patron by field name
    public function test_it_can_count_the_patron_by_field_name()
    {
        $patron = factory(Patron::class)->create();

        $repository = new PatronRepository(new Patron);
        $count = $repository->countBy('patron_no', $patron->patron_no);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_patron_by_field_name()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PatronRepository(new Patron);
        $repository->countBy();
    }

    //Get Top Library User Report
    public function test_it_can_list_the_top_library_user_report()
    {
        $patronType = factory(PatronType::class)->create();
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date, 'patron_type_id' => $patronType->id];

        $repository = new PatronRepository(new Patron);
        $patrons = $repository->getTopLibraryUserReport($data);

        $this->assertInstanceOf(Collection::class, $patrons);
    }

    public function test_it_throws_errors_when_listing_the_top_library_user_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new PatronRepository(new Patron);
        $repository->getTopLibraryUserReport([]);
    }

    //Get Top Borrower Report
    public function test_it_can_list_the_top_borrower_report()
    {
        $patronType = factory(PatronType::class)->create();
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date, 'patron_type_id' => $patronType->id];

        $repository = new PatronRepository(new Patron);
        $patrons = $repository->getTopBorrowerReport($data);

        $this->assertInstanceOf(Collection::class, $patrons);
    }

    public function test_it_throws_errors_when_listing_the_top_borrower_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new PatronRepository(new Patron);
        $repository->getTopBorrowerReport([]);
    }
}
