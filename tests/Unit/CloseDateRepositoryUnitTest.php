<?php

namespace Tests\Unit;

use App\Models\CloseDate;
use App\Repositories\CloseDate\CloseDateRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CloseDateRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Close Date
    public function test_it_can_get_all_the_close_dates()
    {
        factory(CloseDate::class, 3)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $closeDates = $repository->get();

        $this->assertInstanceOf(Collection::class, $closeDates);
    }

    //Paginate Close Date
    public function test_it_can_paginate_the_close_dates()
    {
        factory(CloseDate::class, 3)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $closeDates = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $closeDates);
    }

    //Search Close Date
    public function test_it_can_search_the_close_dates()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $closeDates = $repository->search($closeDate->close_date);

        $this->assertInstanceOf(Collection::class, $closeDates);
    }

    //Get Close Date by field name
    public function test_it_can_get_the_close_dates_by_field_name()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $closeDates = $repository->getBy('close_date', $closeDate->close_date);

        $this->assertInstanceOf(Collection::class, $closeDates);
    }

    //Close Date Data
    public function closeDate()
    {
        return [
            'close_date' => $this->faker->unique()->date,
            'description' => $this->faker->text(191),
        ];
    }

    //Create Close Date
    public function test_it_can_create_the_close_date()
    {
        $repository = new CloseDateRepository(new CloseDate());
        $closeDate = $repository->create($this->closeDate());

        $this->assertInstanceOf(CloseDate::class, $closeDate);
    }

    public function test_it_throws_errors_when_creating_the_close_date()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new CloseDateRepository(new CloseDate());
        $repository->create([]);
    }

    //Find Close Date
    public function test_it_can_find_the_close_date()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $found = $repository->find($closeDate->id);

        $this->assertInstanceOf(CloseDate::class, $found);
        $this->assertEquals($closeDate->close_date, $found->close_date);
    }


    public function test_it_throws_errors_when_finding_the_close_date()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new CloseDateRepository(new CloseDate());
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Close Date by field name
    public function test_it_can_find_the_close_date_by_field_name()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $found = $repository->findBy('close_date', $closeDate->close_date);

        $this->assertInstanceOf(CloseDate::class, $found);
        $this->assertEquals($closeDate->close_date, $found->close_date);
    }


    public function test_it_throws_errors_when_finding_the_close_date_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new CloseDateRepository(new CloseDate());
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Close Date
    public function test_it_can_update_the_close_date()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $updated = $repository->update($data = $this->closeDate(), $closeDate->id);

        $this->assertInstanceOf(CloseDate::class, $updated);
        $this->assertEquals($updated->close_date, $data['close_date']);
    }

    public function test_it_throws_errors_when_updating_the_close_date()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new CloseDateRepository(new CloseDate());
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Close Date
    public function test_it_can_delete_the_close_date()
    {
        $closeDate = factory(CloseDate::class)->create();

        $repository = new CloseDateRepository(new CloseDate());
        $deleted = $repository->delete($closeDate->id);

        $this->assertInstanceOf(CloseDate::class, $deleted);
        $this->assertEquals($closeDate->close_date, $deleted->close_date);
    }

    public function test_it_throws_errors_when_deleting_the_close_date()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new CloseDateRepository(new CloseDate());
        $repository->delete($this->faker->randomNumber(9));
    }
}
