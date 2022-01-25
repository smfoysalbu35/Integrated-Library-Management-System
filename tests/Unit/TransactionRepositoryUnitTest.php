<?php

namespace Tests\Unit;

use App\Models\Patron;
use App\Models\User;
use App\Models\Transaction;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Transaction
    public function test_it_can_get_all_the_transactions()
    {
        factory(Transaction::class, 3)->create();

        $repository = new TransactionRepository(new Transaction);
        $transactions = $repository->get();

        $this->assertInstanceOf(Collection::class, $transactions);
    }

    //Paginate Transaction
    public function test_it_can_paginate_the_transactions()
    {
        factory(Transaction::class, 3)->create();

        $repository = new TransactionRepository(new Transaction);
        $transactions = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $transactions);
    }

    //Get Transaction by field name
    public function test_it_can_get_the_transactions_by_field_name()
    {
        $transaction = factory(Transaction::class)->create();

        $repository = new TransactionRepository(new Transaction);
        $transactions = $repository->getBy('id', $transaction->id);

        $this->assertInstanceOf(Collection::class, $transactions);
    }

    //Get Latest Transaction
    public function test_it_can_get_the_latest_transaction()
    {
        factory(Transaction::class, 3)->create();

        $repository = new TransactionRepository(new Transaction);
        $transactions = $repository->getLatestTransaction();

        $this->assertInstanceOf(Collection::class, $transactions);
    }

    //Transaction Data
    public function transaction()
    {
        $patron = factory(Patron::class)->create();
        $user = factory(User::class)->create();

        return [
            'patron_id' => $patron->id,
            'user_id' => $user->id,
            'transaction_date' => NOW(),
            'transaction_time' => NOW(),
            'total_penalty' => $this->faker->randomNumber(2),
            'payment' => $this->faker->randomNumber(2),
            'change' => $this->faker->randomNumber(2),
        ];
    }

    //Create Transaction
    public function test_it_can_create_the_transaction()
    {
        $repository = new TransactionRepository(new Transaction);
        $transaction = $repository->create($this->transaction());

        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function test_it_throws_errors_when_creating_the_transaction()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new TransactionRepository(new Transaction);
        $repository->create([]);
    }

    //Find Transaction
    public function test_it_can_find_the_transaction()
    {
        $transaction = factory(Transaction::class)->create();

        $repository = new TransactionRepository(new Transaction);
        $found = $repository->find($transaction->id);

        $this->assertInstanceOf(Transaction::class, $found);
        $this->assertEquals($transaction->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_transaction()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new TransactionRepository(new Transaction);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Transaction by field name
    public function test_it_can_find_the_transaction_by_field_name()
    {
        $transaction = factory(Transaction::class)->create();

        $repository = new TransactionRepository(new Transaction);
        $found = $repository->findBy('id', $transaction->id);

        $this->assertInstanceOf(Transaction::class, $found);
        $this->assertEquals($transaction->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_transaction_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new TransactionRepository(new Transaction);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Get Transaction Report
    public function test_it_can_list_the_transaction_report()
    {
        $data = ['from' => $this->faker->date, 'to' => $this->faker->date];

        $repository = new TransactionRepository(new Transaction);
        $transactions = $repository->getTransactionReport($data);

        $this->assertInstanceOf(Collection::class, $transactions);
    }

    public function test_it_throws_errors_when_listing_the_transaction_report()
    {
        $this->expectException(ErrorException::class);

        $repository = new TransactionRepository(new Transaction);
        $repository->getTransactionReport([]);
    }
}
