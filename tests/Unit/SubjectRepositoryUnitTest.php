<?php

namespace Tests\Unit;

use App\Models\Subject;
use App\Repositories\Subject\SubjectRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Subject
    public function test_it_can_get_all_the_subjects()
    {
        factory(Subject::class, 3)->create();

        $repository = new SubjectRepository(new Subject);
        $subjects = $repository->get();

        $this->assertInstanceOf(Collection::class, $subjects);
    }

    //Paginate Subject
    public function test_it_can_paginate_the_subjects()
    {
        factory(Subject::class, 3)->create();

        $repository = new SubjectRepository(new Subject);
        $subjects = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $subjects);
    }

    //Search Subject
    public function test_it_can_search_the_subjects()
    {
        $subject = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $subjects = $repository->search($subject->name);

        $this->assertInstanceOf(Collection::class, $subjects);
    }

    //Get Subject by field name
    public function test_it_can_get_the_subjects_by_field_name()
    {
        $subjects = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $subjects = $repository->getBy('name', $subjects->name);

        $this->assertInstanceOf(Collection::class, $subjects);
    }

    //Subject Data
    public function subject()
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }

    //Create Subject
    public function test_it_can_create_the_subject()
    {
        $repository = new SubjectRepository(new Subject);
        $subject = $repository->create($this->subject());

        $this->assertInstanceOf(Subject::class, $subject);
    }

    public function test_it_throws_errors_when_creating_the_subject()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new SubjectRepository(new Subject);
        $repository->create([]);
    }

    //Find Subject
    public function test_it_can_find_the_subject()
    {
        $subject = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $found = $repository->find($subject->id);

        $this->assertInstanceOf(Subject::class, $found);
        $this->assertEquals($subject->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SubjectRepository(new Subject);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Subject by field name
    public function test_it_can_find_the_subject_by_field_name()
    {
        $subject = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $found = $repository->findBy('name', $subject->name);

        $this->assertInstanceOf(Subject::class, $found);
        $this->assertEquals($subject->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_subject_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SubjectRepository(new Subject);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Subject
    public function test_it_can_update_the_subject()
    {
        $subject = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $updated = $repository->update($data = $this->subject(), $subject->id);

        $this->assertInstanceOf(Subject::class, $updated);
        $this->assertEquals($updated->name, $data['name']);
    }

    public function test_it_throws_errors_when_updating_the_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SubjectRepository(new Subject);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Subject
    public function test_it_can_delete_the_subject()
    {
        $subject = factory(Subject::class)->create();

        $repository = new SubjectRepository(new Subject);
        $deleted = $repository->delete($subject->id);

        $this->assertInstanceOf(Subject::class, $deleted);
        $this->assertEquals($subject->name, $deleted->name);
    }

    public function test_it_throws_errors_when_deleting_the_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SubjectRepository(new Subject);
        $repository->delete($this->faker->randomNumber(9));
    }
}
