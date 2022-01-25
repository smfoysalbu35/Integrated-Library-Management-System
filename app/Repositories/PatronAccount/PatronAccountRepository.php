<?php

namespace App\Repositories\PatronAccount;

use App\Models\PatronAccount;
use App\Repositories\PatronAccount\PatronAccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PatronAccountRepository implements PatronAccountRepositoryInterface
{
    protected $patronAccount;

    public function __construct(PatronAccount $patronAccount)
    {
        $this->patronAccount = $patronAccount;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronAccounts = $this->patronAccount->orderBy($order, $sort)->get();
            return $patronAccounts;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patronAccounts = $this->patronAccount->orderBy($order, $sort)->paginate($perPage);
            return $patronAccounts;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $patronAccounts = $this->patronAccount->where($field, $value)->get();
            return $patronAccounts;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            $patronAccount = new PatronAccount;
            $patronAccount->patron_id = $data['patron_id'];
            $patronAccount->email = $data['email'];
            $patronAccount->password = Hash::make($data['password']);
            $patronAccount->status = 1;
            $patronAccount->save();

            return $patronAccount;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $patronAccount = $this->patronAccount->findOrFail($id);
            return $patronAccount;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $patronAccount = $this->patronAccount->where($field, $value)->firstOrFail();
            return $patronAccount;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $patronAccount = $this->patronAccount->findOrFail($id);
            $patronAccount->delete();

            return $patronAccount;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function count()
    {
        try {
            $count = $this->patronAccount->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function countBy(string $field, string $value)
    {
        try {
            $count = $this->patronAccount->where($field, $value)->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
