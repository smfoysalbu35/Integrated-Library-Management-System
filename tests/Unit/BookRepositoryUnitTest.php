<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Repositories\Book\BookRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Book
    public function test_it_can_get_all_the_books()
    {
        factory(Book::class, 3)->create();

        $repository = new BookRepository(new Book);
        $books = $repository->get();

        $this->assertInstanceOf(Collection::class, $books);
    }

    //Paginate Book
    public function test_it_can_paginate_the_books()
    {
        factory(Book::class, 3)->create();

        $repository = new BookRepository(new Book);
        $books = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $books);
    }

    //Search Book
    public function test_it_can_search_the_books()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $books = $repository->search($book->title);

        $this->assertInstanceOf(Collection::class, $books);
    }

    //Get Book by field name
    public function test_it_can_get_the_books_by_field_name()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $books = $repository->getBy('title', $book->title);

        $this->assertInstanceOf(Collection::class, $books);
    }

    //Book Data
    public function book()
    {
        return [
            'title' => $this->faker->unique()->name,
            'call_number' => $this->faker->isbn10,
            'isbn' => $this->faker->isbn10,

            'edition' => $this->faker->word,
            'volume' => $this->faker->randomNumber(3),

            'publisher' => $this->faker->company,
            'place_publication' => $this->faker->city,

            'copy_right' => $this->faker->year,
            'copy' => $this->faker->randomDigitNot(0),
        ];
    }

    //Create Book
    public function test_it_can_create_the_book()
    {
        $repository = new BookRepository(new Book);
        $book = $repository->create($this->book());

        $this->assertInstanceOf(Book::class, $book);
    }

    public function test_it_throws_errors_when_creating_the_book()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new BookRepository(new Book);
        $repository->create([]);
    }

    //Find Book
    public function test_it_can_find_the_book()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $found = $repository->find($book->id);

        $this->assertInstanceOf(Book::class, $found);
        $this->assertEquals($book->title, $found->title);
    }

    public function test_it_throws_errors_when_finding_the_book()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookRepository(new Book);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Book by field name
    public function test_it_can_find_the_book_by_field_name()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $found = $repository->findBy('title', $book->title);

        $this->assertInstanceOf(Book::class, $found);
        $this->assertEquals($book->title, $found->title);
    }

    public function test_it_throws_errors_when_finding_the_book_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookRepository(new Book);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Book
    public function test_it_can_update_the_book()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $updated = $repository->update($data = $this->book(), $book->id);

        $this->assertInstanceOf(Book::class, $updated);
        $this->assertEquals($updated->title, $data['title']);
    }

    public function test_it_throws_errors_when_updating_the_book()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookRepository(new Book);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Book
    public function test_it_can_delete_the_book()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $deleted = $repository->delete($book->id);

        $this->assertInstanceOf(Book::class, $deleted);
        $this->assertEquals($book->title, $deleted->title);
    }

    public function test_it_throws_errors_when_deleting_the_book()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookRepository(new Book);
        $repository->delete($this->faker->randomNumber(9));
    }

    //Count Book
    public function test_it_can_count_the_book()
    {
        $repository = new BookRepository(new Book);
        $count = $repository->count();

        $this->assertIsInt($count);
    }

    //Count Book by field name
    public function test_it_can_count_the_book_by_field_name()
    {
        $book = factory(Book::class)->create();

        $repository = new BookRepository(new Book);
        $count = $repository->countBy('title', $book->title);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_book_by_field_name()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new BookRepository(new Book);
        $repository->countBy();
    }

    //Get Top Borrowed Book Report
    public function test_it_can_list_the_top_borrowed_book_report()
    {
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new BookRepository(new Book);
        $books = $repository->getTopBorrowedBookReport($data);

        $this->assertInstanceOf(Collection::class, $books);
    }

    public function test_it_throws_errors_when_listing_the_top_borrowed_book_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new BookRepository(new Book);
        $repository->getTopBorrowedBookReport([]);
    }
}
