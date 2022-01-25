<?php

namespace App\Repositories\Penalty;

interface PenaltyRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function getPenaltyByPatronId(int $patronId);

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function findTotalPenaltyByPatronId(int $patronId);

    public function update(array $data, int $id);

    public function getPenaltyCountByPatronId(int $patronId);

    public function getPaidPenaltyReport(array $data);

    public function getUnpaidPenaltyReport(array $data);
}
