<?php

namespace App\Repositories\PatronAccountLog;

use App\Models\PatronAccountLog;
use App\Repositories\PatronAccountLog\PatronAccountLogRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PatronAccountLogRepository implements PatronAccountLogRepositoryInterface
{
    protected $patronAccountLog;

    public function __construct(PatronAccountLog $patronAccountLog)
    {
        $this->patronAccountLog = $patronAccountLog;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronAccountLogs = $this->patronAccountLog->orderBy($order, $sort)->get();
            return $patronAccountLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronAccountLogs = $this->patronAccountLog->orderBy($order, $sort)->paginate($perPage);
            return $patronAccountLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $patronAccountLogs = $this->patronAccountLog->where($field, $value)->get();
            return $patronAccountLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $patronAccountLog = $this->patronAccountLog->findOrFail($id);
            return $patronAccountLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $patronAccountLog = $this->patronAccountLog->where($field, $value)->firstOrFail();
            return $patronAccountLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function login(int $patronAccountId)
    {
        try {
            $patronAccountLog = new PatronAccountLog;
            $patronAccountLog->patron_account_id = $patronAccountId;
            $patronAccountLog->date_in = NOW();
            $patronAccountLog->time_in = NOW();
            $patronAccountLog->status = 1;
            $patronAccountLog->save();

            return $patronAccountLog;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function logout(int $patronAccountId)
    {
        try {
            $patronAccountLog = $this->patronAccountLog->where(['patron_account_id' => $patronAccountId, 'status' => 1])->whereRaw('date_in = CURRENT_DATE()')->latest()->firstOrFail();
            $patronAccountLog->date_out = NOW();
            $patronAccountLog->time_out = NOW();
            $patronAccountLog->status = 0;
            $patronAccountLog->save();

            return $patronAccountLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
