<?php

namespace App\Repositories\UserLog;

use App\Models\UserLog;
use App\Repositories\UserLog\UserLogRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class UserLogRepository implements UserLogRepositoryInterface
{
    protected $userLog;

    public function __construct(UserLog $userLog)
    {
        $this->userLog = $userLog;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $userLogs = $this->userLog->orderBy($order, $sort)->get();
            return $userLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $userLogs = $this->userLog->orderBy($order, $sort)->paginate($perPage);
            return $userLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $userLogs = $this->userLog->where($field, $value)->get();
            return $userLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $userLog = $this->userLog->findOrFail($id);
            return $userLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $userLog = $this->userLog->where($field, $value)->firstOrFail();
            return $userLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function login(int $userId)
    {
        try {
            $userLog = new UserLog;
            $userLog->user_id = $userId;
            $userLog->date_in = NOW();
            $userLog->time_in = NOW();
            $userLog->status = 1;
            $userLog->save();

            return $userLog;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function logout(int $userId)
    {
        try {
            $userLog = $this->userLog->where(['user_id' => $userId, 'status' => 1])->whereRaw('date_in = CURRENT_DATE()')->latest()->firstOrFail();
            $userLog->date_out = NOW();
            $userLog->time_out = NOW();
            $userLog->status = 0;
            $userLog->save();

            return $userLog;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
