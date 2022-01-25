<?php

namespace App\Repositories\Book;

interface BookRepositoryInterface
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

    public function count();

    public function countBy(string $field, string $value);

    public function getTopBorrowedBookReport(array $data);
}
