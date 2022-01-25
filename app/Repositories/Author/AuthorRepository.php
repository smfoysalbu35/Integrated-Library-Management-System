<?php

namespace App\Repositories\Author;

use App\Models\Author;
use App\Repositories\Author\AuthorRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class AuthorRepository implements AuthorRepositoryInterface
{
    protected $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $authors = $this->author->orderBy($order, $sort)->get();
            return $authors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $authors = $this->author->orderBy($order, $sort)->paginate($perPage);
            return $authors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $authors = $this->author->where('name', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $authors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $authors = $this->author->where($field, $value)->get();
            return $authors;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $author = $this->author->create($data);
            return $author;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $author = $this->author->findOrFail($id);
            return $author;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $author = $this->author->where($field, $value)->firstOrFail();
            return $author;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $author = $this->author->findOrFail($id);
            $author->update($data);

            return $author;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $author = $this->author->findOrFail($id);
            $author->delete();

            return $author;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
