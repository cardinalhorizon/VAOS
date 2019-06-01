<?php

namespace App\Http\Controllers\Admin;

use App\Mail\AccountAccepted;
use App\User;
use App\Models\Hub;
use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('admin.users.view', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user     = User::findOrFail($id);
        $airlines = Airline::all();
        $hubs     = Hub::all();

        return view('admin.users.show', ['user' => $user, 'airlines' => $airlines, 'hubs' => $hubs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'vatsim' => 'integer',
            'ivao'   => 'integer',
        ]);

        $user = User::find($id);

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;

        if (strlen($request->cover_url) > 0) {
            $user->cover_url = $request->cover_url;
        } else {
            $user->cover_url = null;
        }

        if (strlen($request->avatar_url) > 0) {
            $user->avatar_url = $request->avatar_url;
        } else {
            $user->avatar_url = null;
        }

        if (strlen($request->vatsim) > 0) {
            $user->vatsim = $request->vatsim;
        } else {
            $user->vatsim = null;
        }

        if (strlen($request->ivao) > 0) {
            $user->ivao = $request->ivao;
        } else {
            $user->ivao = null;
        }
        $user->username = $request->username;
        if ($request->admin == 1) {
            $user->admin = $request->admin;
        } else {
            $user->admin = 0;
        }
        if ($user->status !== $request->status)
        {
            // STATUS CHANGED!!!
            if ($request->status === '1')
            {
                $user->status = $request->status;
                Mail::to($user)->send(new AccountAccepted($user));
            }
            else
            {
                $user->status = $request->status;
            }
        }

        $user->save();

        $request->session()->flash('user_updated', true);

        return redirect('admin/users/'.$id);
    }

    public function airlineMod(Request $request, $id)
    {
        // first let's check to see what the action is.
        if ($request->input('action') === 'add') {
            // Ok, now let's get started. Add the user to the foreign key with the supplied info
            $user = User::find($id);

            $airline = Airline::find($request->input('airline_id'));

            $user->airlines()->attach($airline, [
                'pilot_id' => $request->input('pilotid'),
                'hub_id'   => Hub::find($id),
                'status'   => 1,
                'primary'  => false,
                'admin'    => false,
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function importUsers(Request $request)
    {

        $path = $request->file('file')->storeAS('imports', 'schedule.json');
        //dd($path);
        // Load the Excel Import Object
        $data = json_decode(Storage::get($path), true);
        foreach ($data as $d)
        {
            // Check if the user already exists
            if (!User::where('email', $d['email'])->exists()) {
                $user = User::create([
                    'first_name' => $d['first_name'],
                    'last_name'  => $d['last_name'],
                    'email'      => $d['email'],
                    'password'   => bcrypt('random'),
                    'username'   => $d['username'],
                    'status'     => 1,
                    'admin'      => false,
                ]);
                Mail::to($user)->send(new AccountAccepted($user));
                Password::broker()->sendResetLink(['email'=>$user->email]);
            }
        }
    }
}
