<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsersAPI extends Controller
{
    public function getUsers(Request $request)
    {
        // Very simple API. Just return the user with the name.
        if ($request->query('username')) {
            return User::where('username', $request->query('username'))->first();
        }
    }
}
