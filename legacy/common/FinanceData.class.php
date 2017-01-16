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

class FinanceData extends CodonData {
    
    public static $lasterror;

    public static function formatMoney($number) {
        $isneg = false;
        if ($number < 0) {
            $isneg = true;
        }

        # $ 50.00 - If positive
        # ($ -50.00)  - If negative
        $number = Config::Get('MONEY_UNIT') . ' ' . number_format($number, 2, '.', ', ');

        if ($isneg == true) $number = '(' . $number . ')';

        return $number;
    }

    /**
     * Get the fuel cost, given the airport and the amount of fuel
     *
     * @param int $$fuel_amount Amount of fuel used
     * @param string $apt_icao ICAO of the airport to calculate fuel price
     * @return int Total cost of the fuel
     *
     */
    public static function getFuelPrice($fuel_amount, $apt_icao = '') {

        /* A few steps:
        Check the local cache, if it's not there, or older
        than three days, reach out to the API, if not there,
        then use the localized price
        */
        if ($apt_icao != '') {
            $price = FuelData::GetFuelPrice($apt_icao);
        } else {
            $price = Config::Get('FUEL_DEFAULT_PRICE');
        }

        $total = ($fuel_amount * $price);
        
        # Surcharge amount
         $total += ((Config::Get('FUEL_SURCHARGE') / 100) * $fuel_amount) * $price;

        return $total;
    }


    /**
     * This populates the expenses for a monthly-listing from
     * PIREPData::getIntervalDatabyMonth(array(), months). This goes
     * monthly. Pass in just one month returned by that array. 
     * 
     * This will add a index called 'expenses' which will contain
     * all of the expenses for that month	
     *
     * @param mixed $month_info This is a description
     * @return mixed This is the return value description
     *
     */
    public static function calculateFinances($month_info) {
        
        
        $pilot_pay = LedgerData::getTotalForMonth($month_info->timestamp);
    
        /* Where the bulk of our work is done for expenses */
        $running_total = 0;
        
        $expenses = self::getExpensesForMonth($month_info->timestamp);
        foreach ($expenses as $ex) {
            
            $ex->type = strtolower($ex->type);
            
            if($ex->type == 'm') {
                $ex->total = $ex->cost;
            } elseif($ex->type == 'f') { /* per-flight expense */
                $ex->total = $month_info->total * $ex->cost;
            } elseif($ex->type == 'p') { /* percent of gross per month */
                $ex->total = $month_info->gross * ($ex->cost / 100);
            } elseif($ex->type == 'g') { /* perfect revenue, per flight */
                $ex->total = $month_info->gross * ($ex->cost / 100);
            }

            $running_total += $ex->total;
        }

        $month_info->expenses = $expenses;
        $month_info->expenses_total = $running_total;
        $month_info->pilotpay = $pilot_pay;
        
        $month_info->revenue = 
            $month_info->gross - $month_info->fuelprice - $month_info->pilotpay - $running_total;

        return $month_info;
    }

    /**
     * FinanceData::getExpensesForMonth()
     * 
     * @param mixed $timestamp
     * @return
     */
    public static function getExpensesForMonth($timestamp) {
        
        $time = date('Ym', $timestamp);
        # If it's the current month, just return the latest expenses
        if ($time == date('Ym')) {
            return self::getAllExpenses();
        }

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'expenselog
				WHERE `dateadded`=' . $time;

        $ret = DB::get_results($sql);
        
        if(!$ret) {
            return array();
        }

        return $ret;
    }

    /**
     * FinanceData::setExpensesforMonth()
     * 
     * @param mixed $timestamp
     * @return
     */
    public static function setExpensesforMonth($timestamp) {
        
        $all_expenses = self::getAllExpenses();
        
        if(!$all_expenses || count($all_expenses) == 0) {
            return true;
        }

        # Remove expenses first
        self::removeExpensesforMonth($timestamp);

        $time = date('Ym', $timestamp);
        foreach ($all_expenses as $expense) {
            $sql = 'INSERT INTO ' . TABLE_PREFIX . "expenselog
					(`dateadded`, `name`, `type`, `cost`)
					VALUES ('{$time}', '{$expense->name}', '{$expense->type}', '{$expense->cost}');";

            DB::query($sql);
        }
    }


    /**
     * Populates any expenses which are missing from the table
     * Goes month by month
     *
     */
    public static function updateAllExpenses() {
        $times = StatsData::GetMonthsSinceStart();

        foreach ($times as $timestamp) {
            $exp = self::getExpensesForMonth($timestamp);

            if (!$exp) {
                self::setExpensesforMonth($timestamp);
            }
        }
    }


    /**
     * Re-populates all expenses, deleteing all the old ones
     *
     * @return mixed This is the return value description
     *
     */
    public static function populateAllExpenses() {
        $times = StatsData::GetMonthsSinceStart();

        foreach ($times as $timestamp) {
            self::setExpensesforMonth($timestamp);
        }
    }

    /**
     * FinanceData::removeExpensesforMonth()
     * 
     * @param mixed $timestamp
     * @return void
     */
    public static function removeExpensesforMonth($timestamp) {
        $time = date('Ym', $timestamp);
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'expenselog WHERE `dateadded`=' . $time;
        DB::query($sql);
    }

    /**
     * Get a list of all the expenses
     */
    public static function getAllExpenses() {
        
        $sql = 'SELECT * 
				FROM ' . TABLE_PREFIX . 'expenses';

        $res = DB::get_results($sql);
        if(!$res) {
            return array();
        }
        
        return $res;
    }

    /**
     * Get an expense details based on ID
     */
    public static function getExpenseDetail($id) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'expenses
					WHERE `id`=' . $id;

        return DB::get_row($sql);
    }

    /**
     * Get an expense by the name (mainly to check for
     *	duplicates)
     */
    public static function getExpenseByName($name) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'expenses
					WHERE `name`=' . $name;

        return DB::get_row($sql);
    }

    /**
     * Get all of the expenses for a flight
     */
    public static function getFlightExpenses() {
        $sql = "SELECT * FROM " . TABLE_PREFIX . "expenses WHERE `type`='F'";
        return DB::get_results($sql);
    }


    /**
     *  Get any percentage expenses per flight
     *
     */
    public static function getFlightPercentExpenses() {
        $sql = "SELECT * FROM " . TABLE_PREFIX . "expenses WHERE `type`='G'";
        return DB::get_results($sql);
    }

    /**
     * Add an expense
     */
    public static function addExpense($name, $cost, $type) {

        if ($name == '' || $cost == '') {
            self::$lasterror = 'Name and cost must be entered';
            return false;
        }

        $name = DB::escape($name);
        $cost = DB::escape($cost);
        $type = strtoupper($type);
        if ($type == '') $type = 'M'; // Default as monthly

        $sql = 'INSERT INTO ' . TABLE_PREFIX . "expenses
					 (`name`, `cost`, `type`)
					VALUES('$name', '$cost', '$type')";

        DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Edit a certain expense
     */
    public static function editExpense($id, $name, $cost, $type) {
        
        if ($name == '' || $cost == '') {
            self::$lasterror = 'Name and cost must be entered';
            return false;
        }

        $name = DB::escape($name);
        $cost = DB::escape($cost);
        $type = strtoupper($type);
        if ($type == '') $type = 'M'; // Default as monthly

        $sql = 'UPDATE ' . TABLE_PREFIX . "expenses
					SET `name`='$name', `cost`='$cost', `type`='$type'
					WHERE `id`=$id";

        DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Delete an expense
     */
    public static function removeExpense($id) {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'expenses
					WHERE `id`=' . $id;

        DB::query($sql);
    }

    /**
     * Get the active load count based on the load factor
     *  based on the flight type: P(assenger), C(argo), H(Charter)
     */
    public static function getLoadCount($aircraft_id, $flighttype = 'P') {

        $flighttype = strtoupper($flighttype);

        # Calculate our load factor for this flight
        #	Charter flights always will have a 100% capacity
        if ($flighttype == 'H') {
            $load = 100;
        } else { # Not a charter
            $loadfactor = intval(Config::Get('LOAD_FACTOR'));
            $load = rand($loadfactor - LOAD_VARIATION, $loadfactor + LOAD_VARIATION);

            # Don't allow a load of more than 95%
            if ($load > 95) $load = 95;
            elseif ($load <= 0) $load = 92; # Use ATA standard of 72%
        }

        /*
        * Get the maximum allowed based on the aircraft flown
        */
        $aircraft = OperationsData::GetAircraftInfo($aircraft_id);

        if (!$aircraft) # Aircraft doesn't exist
            {
            if ($flighttype == 'C') # Check cargo if cargo flight
                     $count = Config::Get('DEFAULT_MAX_CARGO_LOAD');
            else  $count = Config::Get('DEFAULT_MAX_PAX_LOAD');
        } else {
            if ($flighttype == 'C') # Check cargo if cargo flight
                     $count = $aircraft->maxcargo;
            else  $count = $aircraft->maxpax;
        }


        $load = ($load / 100);
        $currload = ceil($count * $load);
        return $currload;
    }
}
