<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthAPI extends Controller
{
    /**
     * Handles Authentication for LEGACY ACARS Clients.
     * @param Request $request
     * @return User
     * @return string
     */
    public function acarsLogin(Request $request)
    {
        if ($request->query('format') == 'email') {
            $credentials = array(
                'email' => $request->input('email'),
                'password' => $request->input('password'));
        }
        if ($request->query('format') == 'username') {
            // do some extra work.
            $user = User::where('username', $request->input('username'))->first();
            $credentials = array(
                'email' => $user->email,
                'password' => $request->input('password'));
        }
        if (Auth::validate($credentials))
        {
            if ($request->query('format') == 'username')
                $ret = json_encode(['status' => 200, 'user' => User::where('username', $request->input('username'))->first()]);
            if ($request->query('format') == 'email')
                $ret = json_encode(['status' => 200, 'user' => User::where('email', $request->input('email'))->first()]);
            return $ret;
        }
        else
        {
            return json_encode(['status' => 403]);
        }

    }
}
