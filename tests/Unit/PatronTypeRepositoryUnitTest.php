<?php

namespace Tests\Unit;

use App\Models\PatronType;
use App\Repositories\PatronType\PatronTypeRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronTypeRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Patron Type
    public function test_it_can_get_all_the_patron_types()
    {
        factory(PatronType::class, 3)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $patronTypes = $repository->get();

        $this->assertInstanceOf(Collection::class, $patronTypes);
    }

    //Paginate Patron Type
    public function test_it_can_paginate_the_patron_types()
    {
        factory(PatronType::class, 3)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $patronTypes = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $patronTypes);
    }

    //Search Patron Type
    public function test_it_can_search_the_patron_types()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $patronTypes = $repository->search($patronType->name);

        $this->assertInstanceOf(Collection::class, $patronTypes);
    }

    //Get Patron Type by field name
    public function test_it_can_get_the_patron_types_by_field_name()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $patronTypes = $repository->getBy('name', $patronType->name);

        $this->assertInstanceOf(Collection::class, $patronTypes);
    }

    //Patron Type Data
    public function patronType()
    {
        return [
            'name' => $this->faker->unique()->name,
            'fines' => $this->faker->randomDigitNot(0),
            'no_of_borrow_allowed' => $this->faker->randomDigitNot(0),
            'no_of_day_borrow_allowed' => $this->faker->randomDigitNot(0),
            'no_of_reserve_allowed' => $this->faker->randomDigitNot(0),
            'no_of_day_reserve_allowed' => $this->faker->randomDigitNot(0),
        ];
    }

    //Create Patron Type
    public function test_it_can_create_the_patron_type()
    {
        $repository = new PatronTypeRepository(new PatronType);
        $patronType = $repository->create($this->patronType());

        $this->assertInstanceOf(PatronType::class, $patronType);
    }

    public function test_it_throws_errors_when_creating_the_patron_type()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PatronTypeRepository(new PatronType);
        $repository->create([]);
    }

    //Find Patron Type
    public function test_it_can_find_the_patron_type()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $found = $repository->find($patronType->id);

        $this->assertInstanceOf(PatronType::class, $found);
        $this->assertEquals($patronType->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_patron_type()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronTypeRepository(new PatronType);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Patron Type by field name
    public function test_it_can_find_the_patron_type_by_field_name()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $found = $repository->findBy('name', $patronType->name);

        $this->assertInstanceOf(PatronType::class, $found);
        $this->assertEquals($patronType->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_patron_type_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronTypeRepository(new PatronType);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Patron Type
    public function test_it_can_update_the_patron_type()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $updated = $repository->update($data = $this->patronType(), $patronType->id);

        $this->assertInstanceOf(PatronType::class, $updated);
        $this->assertEquals($updated->name, $data['name']);
    }

    public function test_it_throws_errors_when_updating_the_patron_type()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronTypeRepository(new PatronType);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Patron Type
    public function test_it_can_delete_the_patron_type()
    {
        $patronType = factory(PatronType::class)->create();

        $repository = new PatronTypeRepository(new PatronType);
        $deleted = $repository->delete($patronType->id);

        $this->assertInstanceOf(PatronType::class, $deleted);
        $this->assertEquals($patronType->name, $deleted->name);
    }

    public function test_it_throws_errors_when_deleting_the_patron_type()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronTypeRepository(new PatronType);
        $repository->delete($this->faker->randomNumber(9));
    }
}
