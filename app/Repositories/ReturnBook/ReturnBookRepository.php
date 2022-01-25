<?php

namespace App\Repositories\ReturnBook;

use App\Models\ReturnBook;
use App\Repositories\ReturnBook\ReturnBookRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class ReturnBookRepository implements ReturnBookRepositoryInterface
{
    protected $returnBook;

    public function __construct(ReturnBook $returnBook)
    {
        $this->returnBook = $returnBook;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $returnBooks = $this->returnBook->orderBy($order, $sort)->get();
            return $returnBooks;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $returnBooks = $this->returnBook->orderBy($order, $sort)->paginate($perPage);
            return $returnBooks;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $returnBooks = $this->returnBook->where($field, $value)->get();
            return $returnBooks;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getLatestReturnBookTransaction()
    {
        try {
            $returnBooks = $this->returnBook->latest()->limit(5)->get();
            return $returnBooks;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $returnBook = $this->returnBook->create($data);
            return $returnBook;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $returnBook = $this->returnBook->findOrFail($id);
            return $returnBook;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $returnBook = $this->returnBook->where($field, $value)->firstOrFail();
            return $returnBook;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
