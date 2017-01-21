<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        if(!Schema::hasTable('users')) {
            // Return the view right now
            if ($request->query('mode') == "fresh")
                return view('install.fresh');
            else
                return view('install.start');
        }
        else
        {
            return redirect('/');
        }
    }
    public function doInstall(Request $request)
    {
        if(!Schema::hasTable('users')) {
            // Run the database migration
            $exitcode = Artisan::call('migrate');
            DB::table('users')->insert([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password')),
                'status' => 1,
                'admin' => true
            ]);
            $user = User::find(1);
            Auth::login($user);
            return redirect('/admin');
        }
        else return redirect('/');
    }
}
