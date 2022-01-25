<?php

namespace App\Repositories\Reservation;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Repositories\Reservation\ReservationRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class ReservationRepository implements ReservationRepositoryInterface
{
    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $reservations = $this->reservation->orderBy($order, $sort)->get();
            return $reservations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $reservations = $this->reservation->orderBy($order, $sort)->paginate($perPage);
            return $reservations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $reservations = $this->reservation->where($field, $value)->get();
            return $reservations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getReservationByPatronId(int $patronId)
    {
        try {
            $reservations = $this->reservation->where(['patron_id' => $patronId])->whereRaw('reservation_end_date >= CURRENT_DATE()')->get();
            return $reservations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getLatestReservationTransaction()
    {
        try {
            $reservations = $this->reservation->latest()->limit(5)->get();
            return $reservations;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $reservation = $this->reservation->create($data);
            return $reservation;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $reservation = $this->reservation->findOrFail($id);
            return $reservation;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $reservation = $this->reservation->where($field, $value)->firstOrFail();
            return $reservation;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function getReservationCountByPatronId(int $patronId)
    {
        try {
            $count = $this->reservation->where(['patron_id' => $patronId])->whereRaw('reservation_end_date >= CURRENT_DATE()')->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getReservationCountByAccessionId(int $accessionId)
    {
        try {
            $count = $this->reservation->where(['accession_id' => $accessionId])->whereRaw('reservation_end_date >= CURRENT_DATE()')->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function checkIfPatronReserveAccession(int $accessionId, int $patronId)
    {
        try {
            $count = $this->reservation->where(['accession_id' => $accessionId, 'patron_id' => $patronId])->whereRaw('reservation_end_date >= CURRENT_DATE()')->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
