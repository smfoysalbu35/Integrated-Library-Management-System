<?php

namespace App\Repositories\CloseDate;

use App\Models\CloseDate;
use App\Repositories\CloseDate\CloseDateRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class CloseDateRepository implements CloseDateRepositoryInterface
{
    protected $closeDate;

    public function __construct(CloseDate $closeDate)
    {
        $this->closeDate = $closeDate;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $closeDates = $this->closeDate->orderBy($order, $sort)->get();
            return $closeDates;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $closeDates = $this->closeDate->orderBy($order, $sort)->paginate($perPage);
            return $closeDates;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $closeDates = $this->closeDate->where('close_date', 'like', '%'.$search.'%')
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $closeDates;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $closeDates = $this->closeDate->where($field, $value)->get();
            return $closeDates;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $closeDate = $this->closeDate->create($data);
            return $closeDate;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $closeDate = $this->closeDate->findOrFail($id);
            return $closeDate;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $closeDate = $this->closeDate->where($field, $value)->firstOrFail();
            return $closeDate;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $closeDate = $this->closeDate->findOrFail($id);
            $closeDate->update($data);

            return $closeDate;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $closeDate = $this->closeDate->findOrFail($id);
            $closeDate->delete();

            return $closeDate;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
