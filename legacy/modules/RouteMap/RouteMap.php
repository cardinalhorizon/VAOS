<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
class RouteMap extends CodonModule 
{
	
	/**
	 * RouteMap::index()
	 * 
	 * @return
	 */
	public function index()
	{
		
		if($this->get->maptype == 'hubmap') {
			
			// Show only 20 routes
			$schedule_list = SchedulesData::findSchedules(array(
    				's.depicao'=>$this->get->hub, 
    				's.enabled'=>1
    			), Config::Get('ROUTE_MAP_SHOW_NUMBER')
            );
			
			if(count($schedule_list) == 0) {
				echo 'There are no departures from this airport!';
				return;
			}
			
			$airportinfo = OperationsData::GetAirportInfo($this->get->hub);
			
			echo '<h3>Departures from '.$airportinfo->name.'</h3>';
			
		} else {
			# Get all of the schedule
			$schedule_list = SchedulesData::findSchedules(
                array('s.enabled'=>1), 
                Config::Get('ROUTE_MAP_SHOW_NUMBER')
            );
		}
		
		$this->set('allschedules', $schedule_list); #deprecated
        $this->set('pirep_list', $schedule_list); #pretend these are pireps... 
		$this->render('flown_routes_map.tpl');
	}
}