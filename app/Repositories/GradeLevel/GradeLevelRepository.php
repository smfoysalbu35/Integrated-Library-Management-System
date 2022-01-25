<?php

namespace App\Repositories\GradeLevel;

use App\Models\GradeLevel;
use App\Repositories\GradeLevel\GradeLevelRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class GradeLevelRepository implements GradeLevelRepositoryInterface
{
    protected $gradeLevel;

    public function __construct(GradeLevel $gradeLevel)
    {
        $this->gradeLevel = $gradeLevel;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $gradeLevels = $this->gradeLevel->orderBy($order, $sort)->get();
            return $gradeLevels;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $gradeLevels = $this->gradeLevel->orderBy($order, $sort)->paginate($perPage);
            return $gradeLevels;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $gradeLevels = $this->gradeLevel->where('grade_level', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $gradeLevels;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $gradeLevels = $this->gradeLevel->where($field, $value)->get();
            return $gradeLevels;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $gradeLevel = $this->gradeLevel->create($data);
            return $gradeLevel;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $gradeLevel = $this->gradeLevel->findOrFail($id);
            return $gradeLevel;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $gradeLevel = $this->gradeLevel->where($field, $value)->firstOrFail();
            return $gradeLevel;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $gradeLevel = $this->gradeLevel->findOrFail($id);
            $gradeLevel->update($data);

            return $gradeLevel;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $gradeLevel = $this->gradeLevel->findOrFail($id);
            $gradeLevel->delete();

            return $gradeLevel;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
