<?php

namespace App\Repositories\BookAuthor;

use App\Models\BookAuthor;
use App\Repositories\BookAuthor\BookAuthorRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class BookAuthorRepository implements BookAuthorRepositoryInterface
{
    protected $bookAuthor;

    public function __construct(BookAuthor $bookAuthor)
    {
        $this->bookAuthor = $bookAuthor;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookAuthors = $this->bookAuthor->orderBy($order, $sort)->get();
            return $bookAuthors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookAuthors = $this->bookAuthor->orderBy($order, $sort)->paginate($perPage);
            return $bookAuthors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookAuthors = $this->bookAuthor->whereRaw("(SELECT authors.name FROM authors WHERE authors.id = book_authors.author_id) LIKE '%".$search."%'")
                ->orWhereRaw("(SELECT books.title FROM books WHERE books.id = book_authors.book_id) LIKE '%".$search."%'")
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $bookAuthors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $bookAuthors = $this->bookAuthor->where($field, $value)->get();
            return $bookAuthors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $bookAuthor = $this->bookAuthor->create($data);
            return $bookAuthor;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $bookAuthor = $this->bookAuthor->findOrFail($id);
            return $bookAuthor;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $bookAuthor = $this->bookAuthor->where($field, $value)->firstOrFail();
            return $bookAuthor;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $bookAuthor = $this->bookAuthor->findOrFail($id);
            $bookAuthor->update($data);

            return $bookAuthor;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $bookAuthor = $this->bookAuthor->findOrFail($id);
            $bookAuthor->delete();

            return $bookAuthor;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
