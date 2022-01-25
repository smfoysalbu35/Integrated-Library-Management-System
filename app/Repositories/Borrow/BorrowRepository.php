<?php

namespace App\Repositories\Borrow;

use App\Models\Borrow;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class BorrowRepository implements BorrowRepositoryInterface
{
    protected $borrow;

    public function __construct(Borrow $borrow)
    {
        $this->borrow = $borrow;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $borrows = $this->borrow->orderBy($order, $sort)->get();
            return $borrows;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $borrows = $this->borrow->orderBy($order, $sort)->paginate($perPage);
            return $borrows;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $borrows = $this->borrow->where($field, $value)->get();
            return $borrows;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBorrowByPatronId(int $patronId)
    {
        try {
            $borrows = $this->borrow->where(['patron_id' => $patronId, 'status' => 1])->get();
            return $borrows;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getLatestBorrowTransaction()
    {
        try {
            $borrows = $this->borrow->latest()->limit(5)->get();
            return $borrows;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $borrow = $this->borrow->create($data);
            return $borrow;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $borrow = $this->borrow->findOrFail($id);
            return $borrow;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $borrow = $this->borrow->where($field, $value)->firstOrFail();
            return $borrow;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findPatronBorrowAccession(int $accessionId, int $patronId)
    {
        try {
            $borrow = $this->borrow->where(['accession_id' => $accessionId, 'patron_id' => $patronId, 'status' => 1])->firstOrFail();
            return $borrow;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $borrow = $this->borrow->findOrFail($id);
            $borrow->update($data);

            return $borrow;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function getBorrowCountByPatronId(int $patronId)
    {
        try {
            $count = $this->borrow->where(['patron_id' => $patronId, 'status' => 1])->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBorrowCountByAccessionId(int $accessionId)
    {
        try {
            $count = $this->borrow->where(['accession_id' => $accessionId, 'status' => 1])->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function checkIfPatronBorrowAccession(int $accessionId, int $patronId)
    {
        try {
            $count = $this->borrow->where(['accession_id' => $accessionId, 'patron_id' => $patronId, 'status' => 1])->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
