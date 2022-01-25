<?php

namespace App\Repositories\BookSubject;

use App\Models\BookSubject;
use App\Repositories\BookSubject\BookSubjectRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class BookSubjectRepository implements BookSubjectRepositoryInterface
{
    protected $bookSubject;

    public function __construct(BookSubject $bookSubject)
    {
        $this->bookSubject = $bookSubject;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookSubjects = $this->bookSubject->orderBy($order, $sort)->get();
            return $bookSubjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookSubjects = $this->bookSubject->orderBy($order, $sort)->paginate($perPage);
            return $bookSubjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $bookSubjects = $this->bookSubject->whereRaw("(SELECT books.title FROM books WHERE books.id = book_subjects.book_id) LIKE '%".$search."%'")
                ->orWhereRaw("(SELECT subjects.name FROM subjects WHERE subjects.id = book_subjects.subject_id) LIKE '%".$search."%'")
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $bookSubjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $bookSubjects = $this->bookSubject->where($field, $value)->get();
            return $bookSubjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $bookSubject = $this->bookSubject->create($data);
            return $bookSubject;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $bookSubject = $this->bookSubject->findOrFail($id);
            return $bookSubject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $bookSubject = $this->bookSubject->where($field, $value)->firstOrFail();
            return $bookSubject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $bookSubject = $this->bookSubject->findOrFail($id);
            $bookSubject->update($data);

            return $bookSubject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $bookSubject = $this->bookSubject->findOrFail($id);
            $bookSubject->delete();

            return $bookSubject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
