<?php

namespace App\Repositories\Transaction;

interface TransactionRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function getLatestTransaction();

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function getTransactionReport(array $data);
}
