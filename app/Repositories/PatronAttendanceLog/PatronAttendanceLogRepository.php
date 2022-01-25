<?php

namespace App\Repositories\PatronAttendanceLog;

use App\Models\PatronAttendanceLog;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PatronAttendanceLogRepository implements PatronAttendanceLogRepositoryInterface
{
    protected $patronAttendanceLog;

    public function __construct(PatronAttendanceLog $patronAttendanceLog)
    {
        $this->patronAttendanceLog = $patronAttendanceLog;
    }

    public function getBy(string $field, string $value)
    {
        try {
            $patronAttendanceLogs = $this->patronAttendanceLog->where($field, $value)->get();
            return $patronAttendanceLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function checkPatronAttendanceLog(int $patronId)
    {
        try {
            $count = $this->patronAttendanceLog->where(['patron_id' => $patronId, 'status' => 1])->whereRaw('date_in = CURRENT_DATE()')->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function login(int $patronId)
    {
        try {
            $patronAttendanceLog = new PatronAttendanceLog;
            $patronAttendanceLog->patron_id = $patronId;
            $patronAttendanceLog->date_in = NOW();
            $patronAttendanceLog->time_in = NOW();
            $patronAttendanceLog->status = 1;
            $patronAttendanceLog->save();

            return $patronAttendanceLog->fresh();
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function logout(int $patronId)
    {
        try {
            $patronAttendanceLog = $this->patronAttendanceLog->where(['patron_id' => $patronId, 'status' => 1])->whereRaw('date_in = CURRENT_DATE()')->latest()->firstOrFail();
            $patronAttendanceLog->date_out = NOW();
            $patronAttendanceLog->time_out = NOW();
            $patronAttendanceLog->status = 0;
            $patronAttendanceLog->save();

            return $patronAttendanceLog->fresh();
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function getPatronAttendanceLogReport(array $data)
    {
        try {
            $patronAttendanceLogs = $this->patronAttendanceLog->where([
                    ['date_in', '>=', $data['from']],
                    ['date_in', '<=', $data['to']],
                    ['status', '=', 0]
                ])
                ->latest()
                ->get();

            return $patronAttendanceLogs;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
