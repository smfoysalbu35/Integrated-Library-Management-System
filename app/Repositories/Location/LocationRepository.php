<?php

namespace App\Repositories\Location;

use App\Models\Location;
use App\Repositories\Location\LocationRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class LocationRepository implements LocationRepositoryInterface
{
    protected $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $locations = $this->location->orderBy($order, $sort)->get();
            return $locations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $locations = $this->location->orderBy($order, $sort)->paginate($perPage);
            return $locations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $locations = $this->location->where('name', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $locations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $locations = $this->location->where($field, $value)->get();
            return $locations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $location = $this->location->create($data);
            return $location;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $location = $this->location->findOrFail($id);
            return $location;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $location = $this->location->where($field, $value)->firstOrFail();
            return $location;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $location = $this->location->findOrFail($id);
            $location->update($data);

            return $location;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $location = $this->location->findOrFail($id);
            $location->delete();

            return $location;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
