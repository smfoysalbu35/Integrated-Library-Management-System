<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use ArgumentCountError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get User
    public function test_it_can_get_all_the_users()
    {
        factory(User::class, 3)->create();

        $repository = new UserRepository(new User);
        $users = $repository->get();

        $this->assertInstanceOf(Collection::class, $users);
    }

    //Paginate User
    public function test_it_can_paginate_the_users()
    {
        factory(User::class, 3)->create();

        $repository = new UserRepository(new User);
        $users = $repository->paginate(10);

        $this->assertInstanceOf(Jsonable::class, $users);
    }

    //Get User by field name
    public function test_it_can_get_the_users_by_field_name()
    {
        $user = factory(User::class)->create();

        $repository = new UserRepository(new User);
        $users = $repository->getBy('email', $user->email);

        $this->assertInstanceOf(Collection::class, $users);
    }

    //User Data
    protected function user()
    {
        return new Request([
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->lastName,

            'email' => $this->faker->unique()->safeEmail,
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480)->size(100),
            'user_type' => 1,
            'status' => 1,
        ]);
    }

    //Create User
    public function test_it_can_create_the_user()
    {
        $data = $this->user();
        $data['password'] = config('global.default_password');

        $repository = new UserRepository(new User);
        $user = $repository->create($data);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_it_throws_errors_when_creating_the_user()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new UserRepository(new User);
        $repository->create(new Request([]));
    }

    //Find User
    public function test_it_can_find_the_user()
    {
        $user = factory(User::class)->create();

        $repository = new UserRepository(new User);
        $found = $repository->find($user->id);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->email, $found->email);
    }

    public function test_it_throws_errors_when_finding_the_user()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserRepository(new User);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find User by field name
    public function test_it_can_find_the_user_by_field_name()
    {
        $user = factory(User::class)->create();

        $repository = new UserRepository(new User);
        $found = $repository->findBy('email', $user->email);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->email, $found->email);
    }

    public function test_it_throws_errors_when_finding_the_user_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserRepository(new User);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update User
    public function test_it_can_update_the_user()
    {
        $user = factory(User::class)->create();
        $data = $this->user();
        $data['new_password'] = config('global.default_password');

        $repository = new UserRepository(new User);
        $updated = $repository->update($data, $user->id);

        $this->assertInstanceOf(User::class, $updated);
        $this->assertEquals($updated->email, $data['email']);
    }

    public function test_it_throws_errors_when_updating_the_user()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new UserRepository(new User);
        $repository->create(new Request([]), $this->faker->randomNumber(9));
    }

    //Delete User
    public function test_it_can_delete_the_user()
    {
        $user = factory(User::class)->create();

        $repository = new UserRepository(new User);
        $deleted = $repository->delete($user->id);

        $this->assertInstanceOf(User::class, $deleted);
        $this->assertEquals($user->email, $deleted->email);
    }

    public function test_it_throws_errors_when_deleting_the_user()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserRepository(new User);
        $repository->delete($this->faker->randomNumber(9));
    }

    //Count User
    public function test_it_can_count_the_user()
    {
        $repository = new UserRepository(new User);
        $count = $repository->count();

        $this->assertIsInt($count);
    }

    //Count User by field name
    public function test_it_can_count_the_user_by_field_name()
    {
        $user = factory(User::class)->create();

        $repository = new UserRepository(new User);
        $count = $repository->countBy('email', $user->email);

        $this->assertIsInt($count);
    }

    public function test_it_throws_errors_when_counting_the_user_by_field_name()
    {
        $this->expectException(ArgumentCountError::class);

        $repository = new UserRepository(new User);
        $repository->countBy();
    }

    //Check Password
    public function test_it_should_return_true_when_checking_the_password()
    {
        $user = factory(User::class)->create(['password' => Hash::make(config('global.default_password'))]);

        $repository = new UserRepository(new User);
        $verify = $repository->checkPassword(config('global.default_password'), $user->id);

        $this->assertTrue($verify);
    }

    public function test_it_should_return_false_when_checking_the_password()
    {
        $user = factory(User::class)->create(['password' => Hash::make(config('global.default_password'))]);

        $repository = new UserRepository(new User);
        $verify = $repository->checkPassword('unknown', $user->id);

        $this->assertFalse($verify);
    }

    public function test_it_throws_errors_when_checking_the_password()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new UserRepository(new User);
        $repository->checkPassword(config('global.default_password'), $this->faker->randomNumber(9));
    }
}
