<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class phpVMSMigrationController extends Controller
{
    public function index()
    {
        return view('auth.migrateLogin');
    }

    public function doMigration(Request $request)
    {
        // Ok now, let's authenticate him.
        $user = DB::table('legacy_pilots')->where('email', $request->input('email'))->first();
        if (! $user) {
            $request->session()->flash('user_not_found', true);

            return back();
        }
        $hashed = md5($request->input('password').$user['salt']);

        // Ok now check if it matches
        if ($user->password = $hashed) {
            // It matches, so create a new user now in the database and store his current password in the new format.

            return User::create([
                'first_name' => $user['firstname'],
                'last_name'  => $user['lastname'],
                'email'      => $user['email'],
                'password'   => bcrypt($request->input('password')),
                'username'   => strtolower($user['firstname'].$user['lastname']),
                'status'     => 1,
                'admin'      => false,
            ]);
        } else {
            // It's incorrect, so lets let them know the password didn't match.
            $request->session()->flash('password_mismatch', true);

            return back();
        }
    }

    public function applyNewSettings(Request $request)
    {
    }
}
