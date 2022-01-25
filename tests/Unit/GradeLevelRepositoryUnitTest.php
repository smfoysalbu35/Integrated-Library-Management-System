<?php

namespace Tests\Unit;

use App\Models\GradeLevel;
use App\Repositories\GradeLevel\GradeLevelRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GradeLevelRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Grade Level
    public function test_it_can_get_all_the_grade_levels()
    {
        factory(GradeLevel::class, 3)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $gradeLevels = $repository->get();

        $this->assertInstanceOf(Collection::class, $gradeLevels);
    }

    //Paginate Grade Level
    public function test_it_can_paginate_the_grade_levels()
    {
        factory(GradeLevel::class, 3)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $gradeLevels = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $gradeLevels);
    }

    //Search Grade Level
    public function test_it_can_search_the_grade_levels()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $gradeLevels = $repository->search($gradeLevel->grade_level);

        $this->assertInstanceOf(Collection::class, $gradeLevels);
    }

    //Get Grade Level by field name
    public function test_it_can_get_the_grade_levels_by_field_name()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $gradeLevels = $repository->getBy('grade_level', $gradeLevel->grade_level);

        $this->assertInstanceOf(Collection::class, $gradeLevels);
    }

    //Grade Level Data
    public function gradeLevel()
    {
        return [
            'grade_level' => $this->faker->unique()->randomNumber(9),
        ];
    }

    //Create Grade Level
    public function test_it_can_create_the_grade_level()
    {
        $repository = new GradeLevelRepository(new GradeLevel);
        $gradeLevel = $repository->create($this->gradeLevel());

        $this->assertInstanceOf(GradeLevel::class, $gradeLevel);
    }

    public function test_it_throws_errors_when_creating_the_grade_level()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new GradeLevelRepository(new GradeLevel);
        $repository->create([]);
    }

    //Find Grade Level
    public function test_it_can_find_the_grade_level()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $found = $repository->find($gradeLevel->id);

        $this->assertInstanceOf(GradeLevel::class, $found);
        $this->assertEquals($gradeLevel->grade_level, $found->grade_level);
    }

    public function test_it_throws_errors_when_finding_the_grade_level()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new GradeLevelRepository(new GradeLevel);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Grade Level by field name
    public function test_it_can_find_the_grade_level_by_field_name()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $found = $repository->findBy('grade_level', $gradeLevel->grade_level);

        $this->assertInstanceOf(GradeLevel::class, $found);
        $this->assertEquals($gradeLevel->grade_level, $found->grade_level);
    }

    public function test_it_throws_errors_when_finding_the_grade_level_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new GradeLevelRepository(new GradeLevel);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Grade Level
    public function test_it_can_update_the_grade_level()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $updated = $repository->update($data = $this->gradeLevel(), $gradeLevel->id);

        $this->assertInstanceOf(GradeLevel::class, $updated);
        $this->assertEquals($updated->grade_level, $data['grade_level']);
    }

    public function test_it_throws_errors_when_updating_the_grade_level()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new GradeLevelRepository(new GradeLevel);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Grade Level
    public function test_it_can_delete_the_grade_level()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        $repository = new GradeLevelRepository(new GradeLevel);
        $deleted = $repository->delete($gradeLevel->id);

        $this->assertInstanceOf(GradeLevel::class, $deleted);
        $this->assertEquals($gradeLevel->grade_level, $deleted->grade_level);
    }

    public function test_it_throws_errors_when_deleting_the_grade_level()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new GradeLevelRepository(new GradeLevel);
        $repository->delete($this->faker->randomNumber(9));
    }
}
