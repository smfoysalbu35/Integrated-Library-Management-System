<?php

namespace App\Repositories\PatronType;

interface PatronTypeRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function update(array $data, int $id);

    public function delete(int $id);
}
