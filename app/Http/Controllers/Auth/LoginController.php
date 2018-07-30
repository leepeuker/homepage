<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Create a new controller instance.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return void
     */
    public function authenticated($request, $user)
    {
        if (!$user->verified) {

            auth()->logout();

            return back()->with('warning', 'Email address not verified. Please verify your email address with the verification link sent to you.');
        }

        return redirect()->intended($this->redirectPath());
    }
}
