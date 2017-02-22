<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/flightops';

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
        // Check if reCAPTCHA credentials set
        if(!empty(config('recaptcha.public_key') && config('recaptcha.private_key'))) {
            
            // Return with reCAPTCHA validator
            return Validator::make($data, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'username' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
                'g-recaptcha-response' => 'recaptcha',
            ]);

        } else {

            // reCAPTCHA credentials not set
            return Validator::make($data, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'username' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'username' => $data['username'],
            'status' => 1,
            'admin' => false
        ]);

    }
}
