<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        // Return the view right now
    }
    public function doInstall(Request $request)
    {
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
        Auth::login(1);
        return redirect('/admin');
    }
}
