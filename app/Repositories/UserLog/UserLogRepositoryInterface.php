<?php

namespace App\Repositories\UserLog;

interface UserLogRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function login(int $userId);

    public function logout(int $userId);
}
