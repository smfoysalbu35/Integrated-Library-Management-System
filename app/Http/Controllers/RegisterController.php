<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserLog\UserLogRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    protected $user, $userLog;

    public function __construct(UserRepositoryInterface $user, UserLogRepositoryInterface $userLog)
    {
        $this->user = $user;
        $this->userLog = $userLog;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        event(new Registered($user = $this->user->create($request)));

        $this->guard()->login($user);

        $this->userLog->login($this->guard()->user()->id);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
