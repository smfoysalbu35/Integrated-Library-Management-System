<?php

namespace App\Repositories\PatronAccountLog;

interface PatronAccountLogRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function login(int $patronAccountId);

    public function logout(int $patronAccountId);
}
