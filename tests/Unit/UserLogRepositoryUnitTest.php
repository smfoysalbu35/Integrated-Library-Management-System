<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserLog;
use App\Repositories\UserLog\UserLogRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLogRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get User Log
    public function test_it_can_get_all_the_user_logs()
    {
        factory(UserLog::class, 3)->create();

        $repository = new UserLogRepository(new UserLog);
        $userLogs = $repository->get();

        $this->assertInstanceOf(Collection::class, $userLogs);
    }

    //Paginate User Log
    public function test_it_can_paginate_the_user_logs()
    {
        factory(UserLog::class, 3)->create();

        $repository = new UserLogRepository(new UserLog);
        $userLogs = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $userLogs);
    }

    //Get User Log by field name
    public function test_it_can_get_the_user_logs_by_field_name()
    {
        $userLog = factory(UserLog::class)->create();

        $repository = new UserLogRepository(new UserLog);
        $userLogs = $repository->getBy('id', $userLog->id);

        $this->assertInstanceOf(Collection::class, $userLogs);
    }

    //Find User Log
    public function test_it_can_find_the_user_log()
    {
        $userLog = factory(UserLog::class)->create();

        $repository = new UserLogRepository(new UserLog);
        $found = $repository->find($userLog->id);

        $this->assertInstanceOf(UserLog::class, $found);
        $this->assertEquals($userLog->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_user_log()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserLogRepository(new UserLog);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find User Log by field name
    public function test_it_can_find_the_user_log_by_field_name()
    {
        $userLog = factory(UserLog::class)->create();

        $repository = new UserLogRepository(new UserLog);
        $found = $repository->findBy('id', $userLog->id);

        $this->assertInstanceOf(UserLog::class, $found);
        $this->assertEquals($userLog->id, $found->id);
    }

    public function test_it_throws_errors_when_finding_the_user_log_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserLogRepository(new UserLog);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Login
    public function test_it_can_login_the_user_in_user_log()
    {
        $user = factory(User::class)->create();

        $repository = new UserLogRepository(new UserLog);
        $userLog = $repository->login($user->id);

        $this->assertInstanceOf(UserLog::class, $userLog);
    }

    public function test_it_throws_errors_when_user_try_to_login_to_user_log()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new UserLogRepository(new UserLog);
        $repository->login($this->faker->randomNumber(9));
    }

    //Logout
    public function test_it_can_logout_the_user_in_user_log()
    {
        $user = factory(User::class)->create();
        factory(UserLog::class)->create([
            'user_id' => $user->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $repository = new UserLogRepository(new UserLog);
        $userLog = $repository->logout($user->id);

        $this->assertInstanceOf(UserLog::class, $userLog);
    }

    public function test_it_throws_errors_when_user_try_to_logout_to_user_log()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserLogRepository(new UserLog);
        $repository->logout($this->faker->randomNumber(9));
    }
}
