<?php

namespace App\Http\Controllers\ACARS;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Airline;
use App\Http\Requests;

class XAcarsACARS extends Controller
{
	public function flightdata(Request $request)
	{
		$flight = $request->DATA2;
		
		if (strtolower($flight) == 'bid')
		{
			// Bids are not supported just yet so lets error out
			return '0|Bids Not Supported In VAOS Yet';
		}
		// Well we are not handling bids so lets give them what they want!
		else
		{
			if(is_numeric($flight))
			{
				return '0|No Airline Code';
			}
			$flightinfo = $this->getProperFlightNum($flight);
			
			$route = Schedule::where('code', $flightinfo['code'])->where('flightnum', $flightinfo['flightnum'])->first();
			
			// lets separate the eloquent array into a single thing to send to the view
			$rte;
			/*
			foreach ($route as $r)
			{
				$rte->depicao = $r->depicao;
				$rte->arricao = $r->arricao;
				$rte->route = $r->route;
				$rte->registration = $r->registration;
				$rte->flightlevel = $r->flightlevel;
			}
			*/
			$status = '1|flightplan';
			return view('ACARS.xacars')->with(['status' => '1|flightplan', 'routedata' => $route ]); //"1|flightplan $route->depicao $route->arricao $route->arricao $route->route 180 30 IFR $route->registration $route->flightlevel";
		}
	}
	public function acars(Request $request)
	{
		// Success by default
		$outstring = 'Success';
		
		if ($request->DATA2 == 'TEST')
		{
			return view('ACARS.xacars')->with(['status' => '1|OK']);
		}
		
		if ($request->DATA2 == 'BEGINFLIGHT')
		{
			$data = explode('|', $request->DATA3);
			
		}
	}
	private function getProperFlightNum($flightnum)
	{
		if ($flightnum == '') return false;

        $ret = array();
        //$flightnum = strtoupper($flightnum);
        $airlines = Airline::all();
        
        foreach ($airlines as $a) {
            //$a->code = strtoupper($a->code);
            
            if (strpos($flightnum, $a->code) === false) {
                continue;
            }
            
            $ret['code'] = $a->code;
            $ret['flightnum'] = str_ireplace($a->code, '', $flightnum);

            return $ret;
        }

        # Invalid flight number
        $ret['code'] = '';
        $ret['flightnum'] = $flightnum;
        return $ret;
    }
}
