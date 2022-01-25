<?php

namespace App\Repositories\Subject;

use App\Models\Subject;
use App\Repositories\Subject\SubjectRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class SubjectRepository implements SubjectRepositoryInterface
{
    protected $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $subjects = $this->subject->orderBy($order, $sort)->get();
            return $subjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $subjects = $this->subject->orderBy($order, $sort)->paginate($perPage);
            return $subjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $subjects = $this->subject->where('name', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $subjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $subjects = $this->subject->where($field, $value)->get();
            return $subjects;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $subject = $this->subject->create($data);
            return $subject;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $subject = $this->subject->findOrFail($id);
            return $subject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $subject = $this->subject->where($field, $value)->firstOrFail();
            return $subject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $subject = $this->subject->findOrFail($id);
            $subject->update($data);

            return $subject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $subject = $this->subject->findOrFail($id);
            $subject->delete();

            return $subject;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
