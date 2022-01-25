<?php

namespace Tests\Unit;

use App\Models\PatronAccount;
use App\Models\PatronAccountLog;
use App\Repositories\PatronAccountLog\PatronAccountLogRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAccountLogRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Patron Account Log
    public function test_it_can_get_all_the_patron_account_logs()
    {
        factory(PatronAccountLog::class, 3)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $patronAccountLogs = $repository->get();

        $this->assertInstanceOf(Collection::class, $patronAccountLogs);
    }

    //Paginate Patron Account Log
    public function test_it_can_paginate_the_patron_account_logs()
    {
        factory(PatronAccountLog::class, 3)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $patronAccountLogs = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $patronAccountLogs);
    }

    //Get Patron Account Log by field name
    public function test_it_can_get_the_patron_account_logs_by_field_name()
    {
        $patronAccountLog = factory(PatronAccountLog::class)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $patronAccountLogs = $repository->getBy('id', $patronAccountLog->id);

        $this->assertInstanceOf(Collection::class, $patronAccountLogs);
    }

    //Find Patron Account Log
    public function test_it_can_find_the_patron_account_log()
    {
        $patronAccountLog = factory(PatronAccountLog::class)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $found = $repository->find($patronAccountLog->id);

        $this->assertInstanceOf(PatronAccountLog::class, $found);
        $this->assertEquals($patronAccountLog->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_patron_account_log()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Patron Account Log by field name
    public function test_it_can_find_the_patron_account_log_by_field_name()
    {
        $patronAccountLog = factory(PatronAccountLog::class)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $found = $repository->findBy('id', $patronAccountLog->id);

        $this->assertInstanceOf(PatronAccountLog::class, $found);
        $this->assertEquals($patronAccountLog->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_patron_account_log_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Login
    public function test_it_can_login_the_patron_account_in_patron_account_log()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $patronAccountLog = $repository->login($patronAccount->id);

        $this->assertInstanceOf(PatronAccountLog::class, $patronAccountLog);
    }

    public function test_it_throws_errors_when_patron_account_try_to_login_in_patron_account_log()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $repository->login($this->faker->randomNumber(9));
    }

    //Logout
    public function test_it_can_logout_the_patron_account_in_patron_account_log()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        factory(PatronAccountLog::class)->create([
            'patron_account_id' => $patronAccount->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $patronAccountLog = $repository->logout($patronAccount->id);

        $this->assertInstanceOf(PatronAccountLog::class, $patronAccountLog);
    }

    public function test_it_throws_errors_when_patron_account_try_to_logout_in_patron_account_log()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new PatronAccountLogRepository(new PatronAccountLog);
        $repository->logout($this->faker->randomNumber(9));
    }
}
