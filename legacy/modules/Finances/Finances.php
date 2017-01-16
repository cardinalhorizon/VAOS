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

class Finances extends CodonModule
{
	
	public function index()
	{
		$this->viewreport();
	}
	
	public function viewcurrent()
	{
		$this->viewreport();
	}
	
	public function viewexpensechart()
	{
		$type = $this->get->type;
		$type = str_replace('m', '', $type);
		$check = date('Ym', $type);
			
		$finance_data = $this->getmonthly($check);
		$finance_data = FinanceData::calculateFinances($finance_data[0]);
		
		OFCharts::add_data_set('Fuel Costs', floatval($finance_data->fuelprice));
		OFCharts::add_data_set('Pilot Pay', floatval($finance_data->pilotpay));
		
	   // Now expenses
        if (!is_array($finance_data->expenses)) {
            $finance_data->expenses = array();
        }

        foreach ($finance_data->expenses as $expense) {
            OFCharts::add_data_set($expense->name, floatval($expense->total));
        }
		
		echo OFCharts::create_pie_graph('Expenses breakdown');
	}
	
	public function viewmonthchart()
	{
		/**
		 * Check the first letter in the type
		 * m#### - month
		 * y#### - year
		 * 
		 * No type indicates to view the 'overall'
		 */
		$type = $this->get->type;
		if($type[0] == 'y') {
			$type = str_replace('y', '', $type);
			$year = date('Y', $type);
			
			$finance_data = $this->getyearly($year);
			$title = 'Activity for '.$year;
		} else {
			// This should be the last 3 months overview
			# Get the last 3 months
			$months = 3;
			$params['p.accepted'] =  PIREP_ACCEPTED;
			$finance_data = PIREPData::getIntervalDataByMonth($params, $months);
			$title = 'Recent Activity';
		}
		
		$titles = array();
		$gross_data = array();
		$fuel_data = array();
		$expense_data = array();
		
		if(!$finance_data) {
			$finance_data = array();
		}
		
		foreach($finance_data as $month) {
			$titles[] = $month->ym;
			$gross_data[] = intval($month->revenue);
			$fuel_data[] = intval($month->fuelprice); 
			$expense_data[] = intval($month->expenses_total);
		}
		
		// Add each set
		OFCharts::add_data_set($titles, $gross_data, 'Net Profit', '#FF6633');
		OFCharts::add_data_set($titles, $expense_data, 'Expenses', '#2EB800');
		OFCharts::add_data_set($titles, $fuel_data, 'Fuel Costs', '#008AB8');
		
		//echo OFCharts::create_line_graph('Months Balance Data');
		echo OFCharts::create_area_graph($title);
	}
	
	public function viewreport()
	{
		$type = $this->get->type;
		
		/**
		 * Check the first letter in the type
		 * m#### - month
		 * y#### - year
		 * 
		 * No type indicates to view the 'overall'
		 */
		if($type[0] == 'm') {
			$type = str_replace('m', '', $type);
			$period = date('F Y', $type);
			$check = date('Ym', $type);
			
			$finance_data = $this->getmonthly($check);
			$finance_data = FinanceData::calculateFinances($finance_data[0]);
			
			$this->set('title', 'Balance Sheet for '.$period);
			$this->set('month_data', $finance_data);
			$this->render('finance_balancesheet.tpl');
		} elseif($type[0] == 'y') {
			$type = str_replace('y', '', $type);
			$year = date('Y', $type);
			
			$all_finances = $this->getyearly($year);
			
			$this->set('title', 'Balance Sheet for Year '.date('Y', $type));
			$this->set('allfinances', $all_finances);
			$this->set('year', date('Y', $type));
			
			$this->render('finance_summarysheet.tpl');
		} else {
			// This should be the last 3 months overview
			# Get the last 3 months
			$months = 3;
			$params['p.accepted'] =  PIREP_ACCEPTED;
			$finance_data = PIREPData::getIntervalDataByMonth($params, $months);
			
			$this->set('title', 'Balance Sheet for Last 3 Months');
			$this->set('allfinances', $finance_data);
			$this->render('finance_summarysheet.tpl');
		}
	}
	
	protected function getmonthly($yearmonth)
	{
		$params = array(
			'p.accepted' => PIREP_ACCEPTED,
			"DATE_FORMAT(p.submitdate, '%Y%m') = {$yearmonth}"
		);
		
		//$params = array_merge($params, $this->formfilter());
		return PIREPData::getIntervalData($params);
	}
	
	/**
	 * Loop through month by month, and pull any data for that month.
	 * If there's nothing for that month, then blank it 
	 */
	protected function getyearly($year)
	{
		//$params = $this->formfilter();
		$all_finances = array();
		
		$months = StatsData::GetMonthsInRange('January '.$year, 'December '.$year);
		foreach($months as $month) {
			$date_filter = array("DATE_FORMAT(p.submitdate, '%Y%m') = '".date('Ym', $month)."'");
			//$this_filter = array_merge($date_filter, $params);
			
			$data = PIREPData::getIntervalData($date_filter);
			
			if(!$data) {
				$data = new stdClass();
				$data->ym = date('Y-m', $month);
				$data->timestamp = $month;
				$data->total = 0;
				$data->revenue = 0;
				$data->gross = 0;
				$data->fuelprice = 0;
				$data->price = 0;
				$data->expenses = 0;
				$data->pilotpay = 0;
			} else {
				$data = FinanceData::calculateFinances($data[0]);
			}
			
			$all_finances[] = $data;
		}
		
		return $all_finances;
	}
}