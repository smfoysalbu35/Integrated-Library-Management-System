<?php

namespace App\Repositories\PatronAttendanceLog;

interface PatronAttendanceLogRepositoryInterface
{
    public function getBy(string $field, string $value);

    public function checkPatronAttendanceLog(int $patronId);

    public function login(int $patronId);

    public function logout(int $patronId);

    public function getPatronAttendanceLogReport(array $data);
}
