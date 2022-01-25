<?php

namespace App\Repositories\Reservation;

interface ReservationRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function getReservationByPatronId(int $patronId);

    public function getLatestReservationTransaction();

    public function create(array $data);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function getReservationCountByPatronId(int $patronId);

    public function getReservationCountByAccessionId(int $accessionId);

    public function checkIfPatronReserveAccession(int $accessionId, int $patronId);
}
