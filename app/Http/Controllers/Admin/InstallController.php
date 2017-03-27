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
        if(!env('VAOS_Setup')== true) {
            // Return the view right now
            if ($request->query('mode') == "fresh"){
                return view('install.fresh');
            }elseif($request->query('mode') == "settings"){
              $data = $_ENV;
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

            $this->changeEnvironmentVariable('VAOS_Setup',true);

            $user = User::find(1);
            Auth::login($user);

            return redirect('/admin');
        }
        else return redirect('/');
    }

    public function settings(Request $request) {

      $data = $request->all();
      foreach ($data as $key => $value) {
          /*if( $key == "VAOS_ORG_NAME" || $key == "VAOS_ORG_EMAIL") {
                $this->changeEnvironmentVariableSpecial($key,$value);
          }else{
              $this->changeEnvironmentVariable($key,$value);
          }*/
          $this->changeEnvironmentVariable($key,$value);
      }

      return redirect('/setup?mode=fresh');

    }

    public function changeEnvironmentVariable($key,$value)
    {
        $path = base_path('.env');

            $old = env($key);


        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key=".$old, "$key=".$value, file_get_contents($path)
            ));
        }
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