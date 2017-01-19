<?php

namespace App\Http\Controllers\Admin;

use App\Airline;
use App\Classes\AircraftData;
use App\Classes\VAOS_Schedule;
use App\Models\JobProgress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function getSystem(Request $request)
    {

    }
    public function postSystem(Request $request)
    {
        if ($request->query('task') == 'import')
        {
            $progress = new JobProgress([
                'task' => 'import',
                'slug' => 'Import VAOS System',
                'description' => 'Uploading Workbook',
                'totalitems' => 1,
                'itemscompleted' => 0
                ]);

            // Import the File to the file system
            $path = $request->file('file')->store('data');

            // Load the Excel Import Object
            Excel::load($path, function ($reader) {

            });

        }
    }
    public function getAirlines(Request $request)
    {
        $path = $request->file('file')->store('data');
    }
    public function PostAirlines(Request $request)
    {
        $path = $request->file('file')->store('data');
    }
    public function getFleet(Request $request)
    {
        return view('admin.data.import',['route' => 'fleet']);
    }
    public function postFleet(Request $request)
    {
        if ($request->query('action') == 'import')
        {
            /*
            $progress = new JobProgress([
                'task' => 'import',
                'slug' => 'Import Schedule Templates',
                'description' => 'Uploading Workbook',
                'totalitems' => 1,
                'itemscompleted' => 0
            ]);
            */
            // Import the File to the file system
            //dd($request);
            $path = $request->file('file')->store('imports');
            //dd($path);
            // Load the Excel Import Object
            $sheet = Excel::load('storage/app/'.$path, function ($reader) {})->get();

            foreach ($sheet as $row)
            {
                //$airline_id = Airline::where('icao', $row['airline'])->first();
                //$row['airline'] = $airline_id->id;
                $data = [
                    'airline' => number_format($row->airline),
                    'icao' => $row->icao,
                    'name' => $row->name,
                    'manufacturer' => $row->manufacturer,
                    'registration' => $row->registration,
                    'range' => number_format($row->range),
                    'maxgw' => number_format($row->maxgw),
                    'maxpax' => number_format($row->maxpax),
                    'enabled' => number_format($row->enabled)
                ];
                AircraftData::createAircraft($data);
            }
            return redirect('/admin/fleet');
        }
    }
    public function getSchedule(Request $request)
    {
        return view('admin.data.import',['route' => 'schedule']);
    }
    public function postSchedule(Request $request)
    {
        if ($request->query('action') == 'import')
        {
            /*
            $progress = new JobProgress([
                'task' => 'import',
                'slug' => 'Import Schedule Templates',
                'description' => 'Uploading Workbook',
                'totalitems' => 1,
                'itemscompleted' => 0
            ]);
            */
            // Import the File to the file system
            //dd($request);
            $path = $request->file('file')->store('imports');
            //dd($path);
            // Load the Excel Import Object
            $sheet = Excel::load('storage/app/'.$path, function ($reader) {})->get();

            foreach ($sheet as $row)
            {
                $data = [
                    'airline' => number_format($row->airline),
                    'airline' => number_format($row->airline),
                    'flightnum' => $row->flightnum,
                    'depicao' => $row->depicao,
                    'arricao' => $row->arricao,
                    'aircraft_group' => number_format($row->aircraft_group),
                    'type' => number_format($row->type),
                    'enabled' => number_format($row->enabled)
                ];
                VAOS_Schedule::newRoute($data);
            }
            return redirect('/admin/schedule');
        }
    }
}
