<?php

namespace Tests\Unit;

use App\Models\Patron;
use App\Models\PatronAccount;
use App\Repositories\PatronAccount\PatronAccountRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAccountRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Patron Account
    public function test_it_can_get_all_the_patron_accounts()
    {
        factory(PatronAccount::class, 3)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $patronAccounts = $repository->get();

        $this->assertInstanceOf(Collection::class, $patronAccounts);
    }

    //Paginate Patron Account
    public function test_it_can_paginate_the_patron_accounts()
    {
        factory(PatronAccount::class, 3)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $patronAccounts = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $patronAccounts);
    }

    //Get Patron Account by field name
    public function test_it_can_get_the_patron_accounts_by_field_name()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $patronAccounts = $repository->getBy('email', $patronAccount->email);

        $this->assertInstanceOf(Collection::class, $patronAccounts);
    }

    //Patron Account Data
    protected function patronAccount()
    {
        $patron = factory(Patron::class)->create();

        return [
            'patron_id' => $patron->id,
            'email' => $this->faker->unique()->safeEmail,
            'password' => config('global.default_password'),
        ];
    }

    //Create Patron Account
    public function test_it_can_create_the_patron_account()
    {
        $repository = new PatronAccountRepository(new PatronAccount);
        $patronAccount = $repository->create($this->patronAccount());

        $this->assertInstanceOf(PatronAccount::class, $patronAccount);
    }

    public function test_it_throws_errors_when_creating_the_patron_account()
    {
        $this->expectException(ErrorException::class);

        $repository = new PatronAccountRepository(new PatronAccount);
        $repository->create([]);
    }

    //Find Patron Account
    public function test_it_can_find_the_patron_account()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $found = $repository->find($patronAccount->id);

        $this->assertInstanceOf(PatronAccount::class, $found);
        $this->assertEquals($patronAccount->email, $found->email);
    }

    public function test_it_throws_errors_when_finding_the_patron_account()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountRepository(new PatronAccount);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Patron Account by field name
    public function test_it_can_find_the_patron_account_by_field_name()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $found = $repository->findBy('email', $patronAccount->email);

        $this->assertInstanceOf(PatronAccount::class, $found);
        $this->assertEquals($patronAccount->email, $found->email);
    }

    public function test_it_throws_errors_when_finding_the_patron_account_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountRepository(new PatronAccount);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Delete Patron Account
    public function test_it_can_delete_the_patron_account()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $deleted = $repository->delete($patronAccount->id);

        $this->assertInstanceOf(PatronAccount::class, $deleted);
        $this->assertEquals($patronAccount->email, $deleted->email);
    }

    public function test_it_throws_errors_when_deleting_the_patron_account()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountRepository(new PatronAccount);
        $repository->delete($this->faker->randomNumber(9));
    }

    //Count Patron Account
    public function test_it_can_count_the_patron_account()
    {
        $repository = new PatronAccountRepository(new PatronAccount);
        $count = $repository->count();

        $this->assertIsInt($count);
    }

    //Count Patron Account by field name
    public function test_it_can_count_the_patron_account_by_field_name()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountRepository(new PatronAccount);
        $count = $repository->countBy('email', $patronAccount->email);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_patron_account_by_field_name()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new PatronAccountRepository(new PatronAccount);
        $repository->countBy();
    }
}
