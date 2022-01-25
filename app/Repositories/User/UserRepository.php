<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $users = $this->user->orderBy($order, $sort)->get();
            return $users;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $users = $this->user->orderBy($order, $sort)->paginate($perPage);
            return $users;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $users = $this->user->where($field, $value)->get();
            return $users;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $user = new User;
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;

            if($request->has('middle_name') && $request->get('middle_name'))
                $user->middle_name = $request->middle_name;

            $user->email = $request->email;

            if($request->has('password') && $request->get('password'))
                $user->password = Hash::make($request->password);
            else
                $user->password = Hash::make(config('global.default_password'));

            if($request->hasFile('image'))
            {
                $image = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/user/', $image);
                $user->image = '/storage/user/' . $image;
            }else {
                $user->image = '/assets/images/no-avatar.jpg';
            }

            if($request->has('user_type'))
                $user->user_type = $request->user_type;
            else
                $user->user_type = 1;

            if($request->has('status'))
                $user->status = $request->status;
            else
                $user->status = 1;

            $user->save();

            return $user;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            return $user;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $user = $this->user->where($field, $value)->firstOrFail();
            return $user;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;

            if($request->has('middle_name') && $request->get('middle_name'))
                $user->middle_name = $request->middle_name;

            $user->email = $request->email;

            if($request->has('new_password') && $request->get('new_password'))
                $user->password = Hash::make($request->new_password);

            if($request->hasFile('image'))
            {
                $image = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/user/', $image);
                $user->image = '/storage/user/' . $image;
            }

            $user->user_type = $request->user_type;
            $user->status = $request->status;
            $user->save();

            return $user;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();

            return $user;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function count()
    {
        try {
            $count = $this->user->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function countBy(string $field, string $value)
    {
        try {
            $count = $this->user->where($field, $value)->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function checkPassword(string $password, int $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $verify = Hash::check($password, $user->password);

            return $verify;
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }
}
