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
 
class Pilots extends CodonModule
{
	
	/**
	 * Pilots::index()
	 * 
	 * @return
	 */
	public function index()
	{
		// Get all of our hubs, and list pilots by hub
		$allhubs = OperationsData::GetAllHubs();
		
		if(!$allhubs) $allhubs = array();
		
		foreach($allhubs as $hub)
		{
			$this->set('title', $hub->name);
			$this->set('icao', $hub->icao);
			
            $pilot_list = PilotData::findPilots(array('p.hub'=>$hub->icao));
			$this->set('allpilots', $pilot_list); # deprecated
            $this->set('pilot_list', $pilot_list);
								
			$this->render('pilots_list.tpl');
		}
		
		$nohub = PilotData::findPilots(array('p.hub'=>''));
		if(!$nohub) {
			return;
		}
		
		$this->set('title', 'No Hub');
		$this->set('icao', '');
		$this->set('allpilots', $nohub); # deprecated
        $this->set('pilot_list', $nohub);
		$this->render('pilots_list.tpl');
	}
	
	/**
	 * Pilots::reports()
	 * 
	 * @param string $pilotid
	 * @return
	 */
	public function reports($pilotid='')
	{
		if($pilotid == '') {
			$this->set('message', 'No pilot specified!');
			$this->render('core_error.tpl');
			return;
		}
		
        $pirep_list = PIREPData::GetAllReportsForPilot($pilotid);
		
        $this->set('pireps', $pirep_list); # deprecated
        $this->set('pireps_list', $pirep_list);
        
		$this->render('pireps_viewall.tpl');
	}
	
	
	/* Stats stuff for charts */
	
	
	/**
	 * Pilots::statsdaysdata()
	 * 
	 * @param mixed $pilotid
	 * @return
	 */
	public function statsdaysdata($pilotid)
	{
		$data = PIREPData::getIntervalDataByDays(array('p.pilotid'=>$pilotid), 30);
		$this->create_line_graph('Past 30 days PIREPs', $data);
	}
	
	/**
	 * Pilots::statsmonthsdata()
	 * 
	 * @param mixed $pilotid
	 * @return
	 */
	public function statsmonthsdata($pilotid)
	{
		$data = PIREPData::getIntervalDataByMonth(array('p.pilotid'=>$pilotid), 3);
		$this->create_line_graph('Monthly Flight Stats', $data);
	}
	
	/**
	 * Pilots::statsaircraftdata()
	 * 
	 * @param mixed $pilotid
	 * @return
	 */
	public function statsaircraftdata($pilotid)
	{
		$data = StatsData::PilotAircraftFlownCounts($pilotid);
		if(!$data) $data = array();
		
		include CORE_LIB_PATH.'/php-ofc-library/open-flash-chart.php';
		
		$d = array();
		foreach($data as $ac) {
			OFCharts::add_data_set($ac->aircraft, floatval($ac->hours));
		}
		
		echo OFCharts::create_pie_graph('Aircraft Flown');
	}
	
	/**
	 * Pilots::create_line_graph()
	 * 
	 * @param mixed $title
	 * @param mixed $data
	 * @return
	 */
	protected function create_line_graph($title, $data)
	{	
		if(!$data) {
			$data = array();
		}
				
		$bar_values = array();
		$bar_titles = array();
		foreach($data as $val) {
			$bar_titles[] = $val->ym;
			$bar_values[] = floatval($val->total);
		}
	
		OFCharts::add_data_set($bar_titles, $bar_values);
		echo OFCharts::create_area_graph($title);
	}
		
	/**
	 * Pilots::RecentFrontPage()
	 * 
	 * @param integer $count
	 * @return
	 */
	public function RecentFrontPage($count = 5)
	{
        $pilot_list = PilotData::getLatestPilots($count);
		$this->set('pilots', $pilot_list);
        $this->set('pilot_list', $pilot_list);
        
		$this->render('frontpage_recentpilots.tpl');
	}
}