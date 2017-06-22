<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Utilities\Otp;
use function array_first;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);
        $loginCheck = $this->guard()->validate(
            $credentials
        );

        if ($loginCheck) {
            $request->session()->put('login', array_first($credentials));
            return redirect('/login/challenge');
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function challenge()
    {
        return view('auth/challenge');
    }

    public function validateChallenge(Request $request)
    {
        $code = $request->get('code');

        $user = User::whereEmail($request->session()->pull('login'))->firstOrFail();

        $valid = Otp::verify($user->mfaKey, $code);

        if($valid) {
            $request->session()->put('login', $user->email);
            $this->guard()->loginUsingId($user->id);
            return $this->sendLoginResponse($request);
        }

        return redirect()->back()->withErrors(['code' => 'That code is not valid']);
    }
}
