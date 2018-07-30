<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\Mail\VerificationMail;
use App\Mail\NewRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'g-recaptcha-response.required' => 'Please prove you are human!',
        ];

        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => 'required|recaptcha',
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => bin2hex(openssl_random_pseudo_bytes(16))
        ]);
        
        Mail::to(env('MAIL_WEBMASTER', 'lee.peuker@gmail.com'))->send(new NewRegistration($user->email));
        Mail::to($user->email)->send(new VerificationMail($user));

        return $user;
    }
    
    /**
     * Verify a user with the verification token
     *
     * @param  string  $token
     * @return \App\User
     */
    public function verifyUser($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (isset($user) ){
            
            if (!$user->verified) {

                $user->verified = 1;
                $user->save();
                
                $status = "Your email adresse is verified. You can now login.";

            } else {

                $status = "Your email adresse is already verified. You can now login.";
            }

        } else {

            return redirect('/login')->with('warning', "Sorry your verification link seems broken.");
        }
 
        return redirect('/login')->with('status', $status);
    }
    
    
    /**
     * Verify a user
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \App\User
     */
    protected function registered($request, $user)
    {
        $this->guard()->logout();

        return redirect('/login')->with('status', 'Registration successfull! Please check your email inbox to verify your email address.');
    }
}
