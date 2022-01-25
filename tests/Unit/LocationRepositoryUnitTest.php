<?php

namespace Tests\Unit;

use App\Models\Location;
use App\Repositories\Location\LocationRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Location
    public function test_it_can_get_all_the_locations()
    {
        factory(Location::class, 3)->create();

        $repository = new LocationRepository(new Location);
        $locations = $repository->get();

        $this->assertInstanceOf(Collection::class, $locations);
    }

    //Paginate Location
    public function test_it_can_paginate_the_locations()
    {
        factory(Location::class, 3)->create();

        $repository = new LocationRepository(new Location);
        $locations = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $locations);
    }

    //Search Location
    public function test_it_can_search_the_locations()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $locations = $repository->search($location->name);

        $this->assertInstanceOf(Collection::class, $locations);
    }

    //Get Location by field name
    public function test_it_can_get_the_locations_by_field_name()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $locations = $repository->getBy('name', $location->name);

        $this->assertInstanceOf(Collection::class, $locations);
    }

    //Location Data
    public function location()
    {
        return [
            'name' => $this->faker->unique()->name,
            'symbol' => $this->faker->word,
            'allowed' => $this->faker->word,
        ];
    }

    //Create Location
    public function test_it_can_create_the_location()
    {
        $repository = new LocationRepository(new Location);
        $location = $repository->create($this->location());

        $this->assertInstanceOf(Location::class, $location);
    }

    public function test_it_throws_errors_when_creating_the_location()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new LocationRepository(new Location);
        $repository->create([]);
    }

    //Find Location
    public function test_it_can_find_the_location()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $found = $repository->find($location->id);

        $this->assertInstanceOf(Location::class, $found);
        $this->assertEquals($location->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_location()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new LocationRepository(new Location);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Location by field name
    public function test_it_can_find_the_location_by_field_name()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $found = $repository->findBy('name', $location->name);

        $this->assertInstanceOf(Location::class, $found);
        $this->assertEquals($location->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_location_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new LocationRepository(new Location);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Location
    public function test_it_can_update_the_location()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $updated = $repository->update($data = $this->location(), $location->id);

        $this->assertInstanceOf(Location::class, $updated);
        $this->assertEquals($updated->name, $data['name']);
    }

    public function test_it_throws_errors_when_updating_the_location()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new LocationRepository(new Location);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Location
    public function test_it_can_delete_the_location()
    {
        $location = factory(Location::class)->create();

        $repository = new LocationRepository(new Location);
        $deleted = $repository->delete($location->id);

        $this->assertInstanceOf(Location::class, $deleted);
        $this->assertEquals($location->name, $deleted->name);
    }

    public function test_it_throws_errors_when_deleting_the_location()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new LocationRepository(new Location);
        $repository->delete($this->faker->randomNumber(9));
    }
}
