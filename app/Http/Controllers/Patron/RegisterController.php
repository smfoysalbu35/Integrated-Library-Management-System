<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatronAccountRegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronAccount\PatronAccountRepositoryInterface;
use App\Repositories\PatronAccountLog\PatronAccountLogRepositoryInterface;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $patron, $patronAccount, $patronAccountLog;

    public function __construct(PatronRepositoryInterface $patron, PatronAccountRepositoryInterface $patronAccount, PatronAccountLogRepositoryInterface $patronAccountLog)
    {
        $this->patron = $patron;
        $this->patronAccount = $patronAccount;
        $this->patronAccountLog = $patronAccountLog;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('patron-web.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(PatronAccountRegisterRequest $request)
    {
        $patron = $this->patron->findBy('patron_no', $request->patron_no);

        if($this->patronAccount->countBy('patron_id', $patron->id) > 0)
            return redirect()->route('patron-web.register.index')->with(['error' => 'Patron no. is already registered.']);

        $data = $request->except('_token');
        $data['patron_id'] = $patron->id;

        event(new Registered($patronAccount = $this->patronAccount->create($data)));

        $this->guard()->login($patronAccount);

        $this->patronAccountLog->login($this->guard()->user()->id);

        return $this->registered($request, $patronAccount) ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('patron');
    }
}
