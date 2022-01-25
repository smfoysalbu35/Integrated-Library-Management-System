<?php

namespace App\Repositories\Borrow;

interface BorrowRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function getBorrowByPatronId(int $patronId);

    public function getLatestBorrowTransaction();

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function findPatronBorrowAccession(int $accessionId, int $patronId);

    public function update(array $data, int $id);

    public function getBorrowCountByPatronId(int $patronId);

    public function getBorrowCountByAccessionId(int $accessionId);

    public function checkIfPatronBorrowAccession(int $accessionId, int $patronId);
}
