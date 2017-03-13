<?php

namespace App\Http\Controllers\Admin;

use App\Classes\OTF_DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Brotzka\DotenvEditor\DotenvEditor as Env;
use App\User;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        $env = new Env();
        if(!$env->getValue('VAOS_Setup')== true) {
            // Return the view right now
            if ($request->query('mode') == "fresh"){
                return view('install.fresh');
            }elseif($request->query('mode') == "settings"){
              $data = $env->getContent();
              foreach ($data as $key => $value){
                if( $key == "VAOS_ORG_NAME" || $key == "VAOS_ORG_EMAIL") {
                  $value= str_replace('"','',$value);
                  $data[$key] = $value;
                }
              }
              return view('install.settings')->with('data', $data);
            }else{
              return view('install.start');
            }
        }
        else
        {
            return redirect('/');
        }

    }

    public function doInstall(Request $request)
    {
        if(!Schema::hasTable('users')) {
            Artisan::call('key:generate');
            // Run the database migration
            Artisan::call('migrate');
            User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'username' => $request->input('username'),
            'status' => 1,
            'admin' => true
          ]);

            $env = new Env();
            $env->changeEnv([
                'VAOS_Setup' => true
            ]);

            $user = User::find(1);
            Auth::login($user);

            return redirect('/admin');
        }
        else return redirect('/');
    }

    public function settings(Request $request) {

      $data = $request->all();
      $env = new Env();
      foreach ($data as $key => $value) {
          if( $key == "VAOS_ORG_NAME" || $key == "VAOS_ORG_EMAIL") {
            if ($value[0] == '"') {
              $env->changeEnv([
                $key => $value
              ]);
            }else{
              $env->changeEnv([
                $key => '"' . $value . '"'
              ]);
            }
          }else{
              $env->changeEnv([
                  $key => $value
              ]);
          }
      }

      return redirect('/setup?mode=fresh');

    }
    public function phpVMSTransfer(Request $request)
    {
        // Set the database
        $oldDB = new OTF_DB([
            'database' => $request->input('database'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'prefix' => $request->input('prefix')
        ]);
        $aircraft = $oldDB->getTable('aircraft')->get();
        $users = $oldDB->getTable('pilots')->get();
        $pireps = $oldDB->getTable('pireps')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
    }
}