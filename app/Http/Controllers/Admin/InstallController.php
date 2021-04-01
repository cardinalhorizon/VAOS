<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Classes\OTF_DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class InstallController extends Controller
{
    public function index(Request $request)
    {
        if (env('VAOS_Setup') != true) {
            // Return the view right now
            if ($request->query('mode') == 'fresh') {
                return view('install.fresh');
            } elseif ($request->query('mode') == 'settings') {
                $data = $_ENV;

                return view('install.settings')->with('data', $data);
            } else {
                return view('install.welcome');
            }
        } else {
            return redirect('/');
        }
    }

    public function install(Request $request)
    {
        // Run the installer migration logic
        try {
            Artisan::call('key:generate');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Unable to generate encryption key. Full details: {$e}"
            ]);
        }
        // Run the database migration
        try {
            Artisan::call('migrate');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => "Unable to run database migration. Full details: {$e}"
            ]);
        }

        if (! Schema::hasTable('users')) {
            Artisan::call('key:generate');
            // Run the database migration
            Artisan::call('migrate');
            User::create([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'email'      => $request->input('email'),
                'password'   => bcrypt($request->input('password')),
                'username'   => $request->input('username'),
                'status'     => 1,
                'admin'      => true,
            ]);

            $this->changeEnvironmentVariable('VAOS_INSTALLED', true);

            //Removed in the view for the moment
            /*if (App::environment('production')) {
              Artisan::call('config:cache');
            }else{
              $this->changeEnvironmentVariable('APP_DEBUG', TRUE);
            }*/

            $user = User::find(1);
            Auth::login($user);

            return redirect('/admin');
        } else {
            return redirect('/');
        }
    }

    public function settings(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if ($key != '_token') {
                if ($key == 'VAOS_ORG_NAME' || $key == 'VAOS_ORG_EMAIL') {
                    $this->changeEnvironmentVariableSpecial($key, $value);
                    /*}
                    #Removed in the view for the moment
                    elseif ($key == "APP_ENV_Development" || $key == "APP_ENV_Production") {
                        if ($key == "APP_ENV_Development"){
                            $value = 'local';

                        }else{
                            $value = 'production';
                        }
                      $this->changeEnvironmentVariable('APP_ENV', $value);*/
                } else {
                    $this->changeEnvironmentVariable($key, $value);
                }
            }
        }

        return redirect('/setup?mode=fresh');
    }

    private function changeEnvironmentVariable($key, $value)
    {
        $path = base_path('.env');

        if (is_bool(env($key))) {
            $old   = env($key) ? 'true' : 'false';
            $value = $value ? 'true' : 'false';
        } else {
            $old = env($key);
        }

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key.'='.$old, $key.'='.$value, file_get_contents($path)
            ));
        }
    }

    private function changeEnvironmentVariableSpecial($key, $value)
    {
        $path = base_path('.env');

        $old = env($key);

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key.'='.'"'.$old.'"', $key.'='.'"'.$value.'"',
                file_get_contents($path)
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
            'prefix'   => $request->input('prefix'),
        ]);
        $aircraft = $oldDB->getTable('aircraft')->get();
        $users    = $oldDB->getTable('pilots')->get();
        $pireps   = $oldDB->getTable('pireps')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
        $aircraft = $oldDB->getTable('aircraft')->get();
    }
    private function doDatabaseConnectionCheck() {
        try {
            DB::connection()->getPdo();
            return [
                'success' => true,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e
            ];
        }
    }
    private function doWriteCheck() {

    }
    public function integrityCheck(Request $request) {
        switch ($request->get('type')) {
            case 'dbConnection':
                return response()->json($this->doDatabaseConnectionCheck());
                break;
            default:
        }
    }
    public function importSystem(Request $request)
    {
    }

    public function dbMigrate(Request $request)
    {
        Artisan::call('migrate');
        $request->session()->flash('success', true);

        return redirect('/admin/migrations');
    }

    public function viewMigrations()
    {
        $migrations = DB::table('migrations')->get();

        return view('admin.migrations', ['migrations' => $migrations]);
    }
}
