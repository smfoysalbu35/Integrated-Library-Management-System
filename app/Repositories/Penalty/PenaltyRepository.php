<?php

namespace App\Repositories\Penalty;

use App\Models\Penalty;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PenaltyRepository implements PenaltyRepositoryInterface
{
    protected $penalty;

    public function __construct(Penalty $penalty)
    {
        $this->penalty = $penalty;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $penalties = $this->penalty->orderBy($order, $sort)->get();
            return $penalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $penalties = $this->penalty->orderBy($order, $sort)->paginate($perPage);
            return $penalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $penalties = $this->penalty->where($field, $value)->get();
            return $penalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getPenaltyByPatronId(int $patronId)
    {
        try {
            $penalties = $this->penalty->where(['patron_id' => $patronId, 'status' => 1])->get();
            return $penalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $penalty = $this->penalty->create($data);
            return $penalty;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $penalty = $this->penalty->findOrFail($id);
            return $penalty;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $penalty = $this->penalty->where($field, $value)->firstOrFail();
            return $penalty;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findTotalPenaltyByPatronId(int $patronId)
    {
        try {
            $penalty = $this->penalty->selectRaw('SUM(amount) as total_penalty')->where(['patron_id' => $patronId, 'status' => 1])->firstOrFail();
            return $penalty->total_penalty;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $penalty = $this->penalty->findOrFail($id);
            $penalty->update($data);

            return $penalty;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function getPenaltyCountByPatronId(int $patronId)
    {
        try {
            $count = $this->penalty->where(['patron_id' => $patronId, 'status' => 1])->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getPaidPenaltyReport(array $data)
    {
        try {
            $paidPenalties = $this->penalty->where(['status' => 0])
                ->whereRaw("DATE(created_at) >= '".$data['from']."'")
                ->whereRaw("DATE(created_at) <= '".$data['to']."'")
                ->latest()
                ->get();

            return $paidPenalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getUnpaidPenaltyReport(array $data)
    {
        try {
            $unpaidPenalties = $this->penalty->where(['status' => 1])
                ->whereRaw("DATE(created_at) >= '".$data['from']."'")
                ->whereRaw("DATE(created_at) <= '".$data['to']."'")
                ->latest()
                ->get();

            return $unpaidPenalties;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
