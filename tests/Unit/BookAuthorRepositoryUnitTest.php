<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Repositories\BookAuthor\BookAuthorRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookAuthorRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Book Author
    public function test_it_can_get_all_the_book_authors()
    {
        factory(BookAuthor::class, 3)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $bookAuthors = $repository->get();

        $this->assertInstanceOf(Collection::class, $bookAuthors);
    }

    //Paginate Book Author
    public function test_it_can_paginate_the_book_authors()
    {
        factory(BookAuthor::class, 3)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $bookAuthors = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $bookAuthors);
    }

    //Search Book Author
    public function test_it_can_search_the_book_authors()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $bookAuthors = $repository->search($bookAuthor->author->name);

        $this->assertInstanceOf(Collection::class, $bookAuthors);
    }

    //Get Book Author by field name
    public function test_it_can_get_the_book_authors_by_field_name()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $bookAuthors = $repository->getBy('author_id', $bookAuthor->author_id);

        $this->assertInstanceOf(Collection::class, $bookAuthors);
    }

    //Book Author Data
    public function bookAuthor()
    {
        $author = factory(Author::class)->create();
        $book = factory(Book::class)->create();

        return [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ];
    }

    //Create Book Author
    public function test_it_can_create_the_book_author()
    {
        $repository = new BookAuthorRepository(new BookAuthor);
        $bookAuthor = $repository->create($this->bookAuthor());

        $this->assertInstanceOf(BookAuthor::class, $bookAuthor);
    }

    public function test_it_throws_errors_when_creating_the_book_author()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new BookAuthorRepository(new BookAuthor);
        $repository->create([]);
    }

    //Find Book Author
    public function test_it_can_find_the_book_author()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $found = $repository->find($bookAuthor->id);

        $this->assertInstanceOf(BookAuthor::class, $found);
        $this->assertEquals($bookAuthor->author_id, $found->author_id);
    }

    public function test_it_throws_errors_when_finding_the_book_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookAuthorRepository(new BookAuthor);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Book Author by field name
    public function test_it_can_find_the_book_author_by_field_name()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $found = $repository->findBy('author_id', $bookAuthor->author_id);

        $this->assertInstanceOf(BookAuthor::class, $found);
        $this->assertEquals($bookAuthor->author_id, $found->author_id);
    }

    public function test_it_throws_errors_when_finding_the_book_author_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookAuthorRepository(new BookAuthor);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Book Author
    public function test_it_can_update_the_book_author()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $updated = $repository->update($data = $this->bookAuthor(), $bookAuthor->id);

        $this->assertInstanceOf(BookAuthor::class, $updated);
        $this->assertEquals($updated->author_id, $data['author_id']);
    }

    public function test_it_throws_errors_when_updating_the_book_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookAuthorRepository(new BookAuthor);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Book Author
    public function test_it_can_delete_the_book_author()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $repository = new BookAuthorRepository(new BookAuthor);
        $deleted = $repository->delete($bookAuthor->id);

        $this->assertInstanceOf(BookAuthor::class, $deleted);
        $this->assertEquals($bookAuthor->author_id, $deleted->author_id);
    }

    public function test_it_throws_errors_when_deleting_the_book_author()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookAuthorRepository(new BookAuthor);
        $repository->delete($this->faker->randomNumber(9));
    }
}
