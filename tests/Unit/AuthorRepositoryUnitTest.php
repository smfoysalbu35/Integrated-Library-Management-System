<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Repositories\Author\AuthorRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Author
    public function test_it_can_get_all_the_authors()
    {
        factory(Author::class, 3)->create();

        $repository = new AuthorRepository(new Author);
        $authors = $repository->get();

        $this->assertInstanceOf(Collection::class, $authors);
    }

    //Paginate Author
    public function test_it_can_paginate_the_authors()
    {
        factory(Author::class, 3)->create();

        $repository = new AuthorRepository(new Author);
        $authors = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $authors);
    }

    //Search Author
    public function test_it_can_search_the_authors()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $authors = $repository->search($author->name);

        $this->assertInstanceOf(Collection::class, $authors);
    }

    //Get Author by field name
    public function test_it_can_get_the_authors_by_field_name()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $authors = $repository->getBy('name', $author->name);

        $this->assertInstanceOf(Collection::class, $authors);
    }

    //Author Data
    public function author()
    {
        return [
            'name' => $this->faker->unique()->name,
        ];
    }

    //Create Author
    public function test_it_can_create_the_author()
    {
        $repository = new AuthorRepository(new Author);
        $author = $repository->create($this->author());

        $this->assertInstanceOf(Author::class, $author);
    }

    public function test_it_throws_errors_when_creating_the_author()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new AuthorRepository(new Author);
        $repository->create([]);
    }

    //Find Author
    public function test_it_can_find_the_author()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $found = $repository->find($author->id);

        $this->assertInstanceOf(Author::class, $found);
        $this->assertEquals($author->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AuthorRepository(new Author);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Author by field name
    public function test_it_can_find_the_author_by_field_name()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $found = $repository->findBy('name', $author->name);

        $this->assertInstanceOf(Author::class, $found);
        $this->assertEquals($author->name, $found->name);
    }

    public function test_it_throws_errors_when_finding_the_author_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AuthorRepository(new Author);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Author
    public function test_it_can_update_the_author()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $updated = $repository->update($data = $this->author(), $author->id);

        $this->assertInstanceOf(Author::class, $updated);
        $this->assertEquals($updated->name, $data['name']);
    }

    public function test_it_throws_errors_when_updating_the_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AuthorRepository(new Author);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Author
    public function test_it_can_delete_the_author()
    {
        $author = factory(Author::class)->create();

        $repository = new AuthorRepository(new Author);
        $deleted = $repository->delete($author->id);

        $this->assertInstanceOf(Author::class, $deleted);
        $this->assertEquals($author->name, $deleted->name);
    }

    public function test_it_throws_errors_when_deleting_the_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new AuthorRepository(new Author);
        $repository->delete($this->faker->randomNumber(9));
    }
}
