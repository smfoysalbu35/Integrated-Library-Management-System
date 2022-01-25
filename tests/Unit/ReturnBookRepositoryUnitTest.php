<?php

namespace Tests\Unit;

use App\Models\Borrow;
use App\Models\ReturnBook;
use App\Repositories\ReturnBook\ReturnBookRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReturnBookRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Return Book
    public function test_it_can_get_all_the_return_books()
    {
        factory(ReturnBook::class, 3)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $returnBooks = $repository->get();

        $this->assertInstanceOf(Collection::class, $returnBooks);
    }

    //Paginate Return Book
    public function test_it_can_paginate_the_return_books()
    {
        factory(ReturnBook::class, 3)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $returnBooks = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $returnBooks);
    }

    //Get Return Book by field name
    public function test_it_can_get_the_return_books_by_field_name()
    {
        $returnBook = factory(ReturnBook::class)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $returnBooks = $repository->getBy('id', $returnBook->id);

        $this->assertInstanceOf(Collection::class, $returnBooks);
    }

    //Get Latest Return Book Transaction
    public function test_it_can_get_the_latest_return_book_transaction()
    {
        factory(ReturnBook::class, 3)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $returnBooks = $repository->getLatestReturnBookTransaction();

        $this->assertInstanceOf(Collection::class, $returnBooks);
    }

    //Return Book Data
    public function returnBook()
    {
        $borrow = factory(Borrow::class)->create();

        return [
            'borrow_id' => $borrow->id,
            'patron_id' => $borrow->patron->id,
            'accession_id' => $borrow->accession->id,
            'return_date' => NOW(),
            'return_time' => NOW(),
        ];
    }

    //Create Return Book
    public function test_it_can_create_the_return_book()
    {
        $repository = new ReturnBookRepository(new ReturnBook);
        $returnBook = $repository->create($this->returnBook());

        $this->assertInstanceOf(ReturnBook::class, $returnBook);
    }

    public function test_it_throws_errors_when_creating_the_return_book()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new ReturnBookRepository(new ReturnBook);
        $repository->create([]);
    }

    //Find Return Book
    public function test_it_can_find_the_return_book()
    {
        $returnBook = factory(ReturnBook::class)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $found = $repository->find($returnBook->id);

        $this->assertInstanceOf(ReturnBook::class, $found);
        $this->assertEquals($returnBook->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_return_book()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new ReturnBookRepository(new ReturnBook);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Return Book by field name
    public function test_it_can_find_the_return_book_by_field_name()
    {
        $returnBook = factory(ReturnBook::class)->create();

        $repository = new ReturnBookRepository(new ReturnBook);
        $found = $repository->findBy('id', $returnBook->id);

        $this->assertInstanceOf(ReturnBook::class, $found);
        $this->assertEquals($returnBook->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_return_book_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new ReturnBookRepository(new ReturnBook);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }
}
