<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ExtHour;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator;

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
    protected $redirectTo = '/status';

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
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $input)
    {
        $array              = json_decode($input['data']);
        $data['first_name'] = $array->userData->first_name;
        $data['last_name']  = $array->userData->last_name;
        $data['username']   = $array->userData->username;
        $data['email']      = $array->userData->email;
        $data['password']   = $array->userData->password;
        // reCAPTCHA credentials not set
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'username'   => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $input
     *
     * @return User
     */
    protected function create(array $input)
    {
        // Split the create user array into something we can actually read
        $data = json_decode($input['data']);
        //dd($data);
        // Ok, now let's create the user account.
        $user = User::create([
            'first_name' => $data->userData->first_name,
            'last_name'  => $data->userData->last_name,
            'email'      => $data->userData->email,
            'password'   => bcrypt($data->userData->password),
            'username'   => $data->userData->username,
            'status'     => 0,
            'admin'      => false,
        ]);
        // Ok folks, time to get those external hours thrown in.

        foreach ($data->externalHours as $hrs) {
            $ext = new ExtHour();

            $ext->user()->associate($user);
            $ext->name       = $hrs->name;
            $ext->total      = $hrs->total;
            $ext->source_url = $hrs->source_url;
        }

        return $user;
    }
}
