<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $transactions = $this->transaction->orderBy($order, $sort)->get();
            return $transactions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $transactions = $this->transaction->orderBy($order, $sort)->paginate($perPage);
            return $transactions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $transactions = $this->transaction->where($field, $value)->get();
            return $transactions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getLatestTransaction()
    {
        try {
            $transactions = $this->transaction->latest()->limit(5)->get();
            return $transactions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $transaction = $this->transaction->create($data);
            return $transaction;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $transaction = $this->transaction->with([
                    'transaction_details' => function ($query) {
                        return $query->select('id', 'transaction_id', 'accession_id', 'penalty_id')
                            ->with([
                                'accession' => function ($query) {
                                    return $query->select('id', 'accession_no', 'book_id', 'status')->with('book:id,title,call_number,isbn');
                                }
                            ])
                            ->with('penalty:id,penalty_due_date,overdue,amount,status');
                    }
                ])
                ->findOrFail($id);

            return $transaction;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $transaction = $this->transaction->with([
                    'transaction_details' => function ($query) {
                        return $query->select('id', 'transaction_id', 'accession_id', 'penalty_id')
                            ->with([
                                'accession' => function ($query) {
                                    return $query->select('id', 'accession_no', 'book_id', 'status')->with('book:id,title,call_number,isbn');
                                }
                            ])
                            ->with('penalty:id,penalty_due_date,overdue,amount,status');
                    }
                ])
                ->where($field, $value)
                ->firstOrFail();

            return $transaction;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function getTransactionReport(array $data)
    {
        try {
            $transactions = $this->transaction->whereRaw("transaction_date >= '".$data['from']."'")
                ->whereRaw("transaction_date <= '".$data['to']."'")
                ->latest()
                ->get();

            return $transactions;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
