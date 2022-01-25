<?php

namespace App\Repositories\PatronAccount;

interface PatronAccountRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function delete(int $id);

    public function count();

    public function countBy(string $field, string $value);
}
