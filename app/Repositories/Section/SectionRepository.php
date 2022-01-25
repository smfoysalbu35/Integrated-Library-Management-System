<?php

namespace App\Repositories\Section;

use App\Models\Section;
use App\Repositories\Section\SectionRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class SectionRepository implements SectionRepositoryInterface
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $sections = $this->section->orderBy($order, $sort)->get();
            return $sections;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $sections = $this->section->orderBy($order, $sort)->paginate($perPage);
            return $sections;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $sections = $this->section->where('name', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $sections;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $sections = $this->section->where($field, $value)->get();
            return $sections;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $section = $this->section->create($data);
            return $section;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $section = $this->section->findOrFail($id);
            return $section;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $section = $this->section->where($field, $value)->firstOrFail();
            return $section;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $section = $this->section->findOrFail($id);
            $section->update($data);

            return $section;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $section = $this->section->findOrFail($id);
            $section->delete();

            return $section;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
