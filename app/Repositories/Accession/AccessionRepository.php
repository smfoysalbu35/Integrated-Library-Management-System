<?php

namespace App\Repositories\Accession;

use App\Models\Accession;
use App\Repositories\Accession\AccessionRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class AccessionRepository implements AccessionRepositoryInterface
{
    protected $accession;

    public function __construct(Accession $accession)
    {
        $this->accession = $accession;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $accessions = $this->accession->orderBy($order, $sort)->get();
            return $accessions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $accessions = $this->accession->orderBy($order, $sort)->paginate($perPage);
            return $accessions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function search(string $search = null, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $accessions = $this->accession->where('accession_no', 'like', '%'.$search.'%')
                ->orWhereRaw("(SELECT books.title FROM books WHERE books.id = accessions.book_id) like '%".$search."%'")
                ->limit(is_null($search) ? 10 : 100)
                ->orderBy($order, $sort)
                ->get();

            return $accessions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $accessions = $this->accession->where($field, $value)->get();
            return $accessions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $accession = $this->accession->create($data);
            return $accession;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $accession = $this->accession->findOrFail($id);
            return $accession;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $accession = $this->accession->where($field, $value)->firstOrFail();
            return $accession;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $accession = $this->accession->findOrFail($id);
            $accession->update($data);

            return $accession;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $accession = $this->accession->findOrFail($id);
            $accession->delete();

            return $accession;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function count()
    {
        try {
            $count = $this->accession->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function countBy(string $field, string $value)
    {
        try {
            $count = $this->accession->where($field, $value)->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getAcquisitionReport(array $data)
    {
        try {
            $accessions = $this->accession->where([
                    ['acquired_date', '>=', $data['from']],
                    ['acquired_date', '<=', $data['to']]
                ])
                ->latest()
                ->get();

            return $accessions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
