<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Notifications\PirepFiled;
use Illuminate\Http\Request;

class PIREPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($airline, Request $request)
    {
        $pireps = Flight::where(['status' => 0, 'state' => 2])->with('user', 'depapt', 'arrapt', 'aircraft')->get();

        return view('admin.pireps.pending', ['pireps' => $pireps]);
        //return response()->json($pireps);
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
        $pirep = PIREP::where('id', $id)->with('airline')->with('depapt')->with('arrapt')->with('aircraft')->with('user')->first();

        return view('admin.pireps.detailed', ['p' => $pirep]);
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
    public function update($airline, $id, Request $request)
    {
        $pirep = Flight::find($id);
        // check if we are only changing the status
        //dd($request);
        if ($request->input('flag') == 'status') {
            $pirep->status = $request->input('status');
            //dd($pirep->status);
            $pirep->save();
            //$user->notify(new PirepFiled($pirep));
            // Ok let's determine where to send the guy. Back to previous I assume
            return redirect()->back();
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
}
