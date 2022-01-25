<?php

namespace App\Repositories\Book;

use App\Models\Book;
use App\Repositories\Book\BookRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class BookRepository implements BookRepositoryInterface
{
    protected $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $books = $this->book->orderBy($order, $sort)->get();
            return $books;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $books = $this->book->orderBy($order, $sort)->paginate($perPage);
            return $books;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $books = $this->book->where('title', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $books;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $books = $this->book->where($field, $value)->get();
            return $books;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $book = $this->book->create($data);
            return $book;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $book = $this->book->findOrFail($id);
            return $book;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $book = $this->book->where($field, $value)->firstOrFail();
            return $book;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $book = $this->book->findOrFail($id);
            $book->update($data);

            return $book;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $book = $this->book->findOrFail($id);
            $book->delete();

            return $book;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function count()
    {
        try {
            $count = $this->book->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function countBy(string $field, string $value)
    {
        try {
            $count = $this->book->where($field, $value)->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getTopBorrowedBookReport(array $data)
    {
        try {
            $topBorrowedBooks = $this->book->select('id', 'title', 'call_number', 'isbn')
                ->selectRaw("(SELECT COUNT(*) FROM borrows INNER JOIN accessions ON accessions.id = borrows.accession_id
                    WHERE accessions.book_id = books.id
                    AND borrows.borrow_date >= '".$data['from']."'
                    AND borrows.borrow_date <= '".$data['to']."') as no_of_borrow")

                ->whereRaw("(SELECT COUNT(*) FROM borrows INNER JOIN accessions ON accessions.id = borrows.accession_id
                    WHERE accessions.book_id = books.id
                    AND borrows.borrow_date >= '".$data['from']."'
                    AND borrows.borrow_date <= '".$data['to']."') > 0")

                ->orderBy('no_of_borrow', 'DESC')
                ->limit(10)
                ->get();

            return $topBorrowedBooks;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
