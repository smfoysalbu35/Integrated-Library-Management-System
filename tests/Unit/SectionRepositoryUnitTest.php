<?php

namespace Tests\Unit;

use App\Models\GradeLevel;
use App\Models\Section;
use App\Repositories\Section\SectionRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SectionRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Section
    public function test_it_can_get_all_the_sections()
    {
        factory(Section::class, 3)->create();

        $repository = new SectionRepository(new Section);
        $sections = $repository->get();

        $this->assertInstanceOf(Collection::class, $sections);
    }

    //Paginate Section
    public function test_it_can_paginate_the_sections()
    {
        factory(Section::class, 3)->create();

        $repository = new SectionRepository(new Section);
        $sections = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $sections);
    }

    //Search Section
    public function test_it_can_search_the_sections()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $sections = $repository->search($section->name);

        $this->assertInstanceOf(Collection::class, $sections);
    }

    //Get Section by field name
    public function test_it_can_get_the_sections_by_field_name()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $sections = $repository->getBy('name', $section->name);

        $this->assertInstanceOf(Collection::class, $sections);
    }

    //Section Data
    public function section()
    {
        $gradeLevel = factory(GradeLevel::class)->create();

        return [
            'name' => $this->faker->unique()->name,
            'grade_level_id' => $gradeLevel->id,
        ];
    }

    //Create Section
    public function test_it_can_create_the_section()
    {
        $repository = new SectionRepository(new Section);
        $section = $repository->create($this->section());

        $this->assertInstanceOf(Section::class, $section);
    }

    public function test_it_throws_errors_when_creating_the_section()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new SectionRepository(new Section);
        $repository->create([]);
    }

    //Find Section
    public function test_it_can_find_the_section()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $found = $repository->find($section->id);

        $this->assertInstanceOf(Section::class, $found);
        $this->assertEquals($section->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_section()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SectionRepository(new Section);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Section by field name
    public function test_it_can_find_the_section_by_field_name()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $found = $repository->findBy('name', $section->name);

        $this->assertInstanceOf(Section::class, $found);
        $this->assertEquals($section->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_section_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SectionRepository(new Section);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Section
    public function test_it_can_update_the_section()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $updated = $repository->update($data = $this->section(), $section->id);

        $this->assertInstanceOf(Section::class, $updated);
        $this->assertEquals($updated->name, $data['name']);
    }

    public function test_it_throws_errors_when_updating_the_section()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SectionRepository(new Section);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Section
    public function test_it_can_delete_the_section()
    {
        $section = factory(Section::class)->create();

        $repository = new SectionRepository(new Section);
        $deleted = $repository->delete($section->id);

        $this->assertInstanceOf(Section::class, $deleted);
        $this->assertEquals($section->name, $deleted->name);
    }

    public function test_it_throws_errors_when_deleting_the_section()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new SectionRepository(new Section);
        $repository->delete($this->faker->randomNumber(9));
    }
}
