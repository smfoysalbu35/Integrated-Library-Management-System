<?php

namespace App\Repositories\PatronType;

use App\Models\PatronType;
use App\Repositories\PatronType\PatronTypeRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PatronTypeRepository implements PatronTypeRepositoryInterface
{
    protected $patronType;

    public function __construct(PatronType $patronType)
    {
        $this->patronType = $patronType;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronTypes = $this->patronType->orderBy($order, $sort)->get();
            return $patronTypes;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronTypes = $this->patronType->orderBy($order, $sort)->paginate($perPage);
            return $patronTypes;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronTypes = $this->patronType->where('name', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $patronTypes;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $patronTypes = $this->patronType->where($field, $value)->get();
            return $patronTypes;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $patronType = $this->patronType->create($data);
            return $patronType;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $patronType = $this->patronType->findOrFail($id);
            return $patronType;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $patronType = $this->patronType->where($field, $value)->firstOrFail();
            return $patronType;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $patronType = $this->patronType->findOrFail($id);
            $patronType->update($data);

            return $patronType;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $patronType = $this->patronType->findOrFail($id);
            $patronType->delete();

            return $patronType;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
