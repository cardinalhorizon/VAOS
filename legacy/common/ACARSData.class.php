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

require "../vendor/autoload.php";

use \GuzzleHttp\Client as Guzzle;

class ACARSData extends CodonData {

    public static $lasterror;
    public static $pirepid;

    /**
     * This updates the ACARS live data for a pilot
     *
     * @param mixed $data This is the data structure with flight properties
     * @return mixed Nothing
     *
     */
    public static function updateFlightData($pilotid, $data) {

        // Send the data out of phpVMS and directly into VAOS

        $client = new Guzzle();

        $ret = $client->request('POST', VAOS_URL.'api/1_0/acars/posrpt', [
            'query' => [
                'format' => 'phpVMS'
            ],
            'form_params' => $data
        ])->getBody();

        $jdec = json_decode($ret, true);
        if ($jdec['status'] == 200)
        {
            return true;
        }
        else
        {
            return false;
        }

        /*
        if (!is_array($data)) {
            self::$lasterror = 'Data not array';
            return false;
        }

        if (isset($data['code']) && isset($data['flightnum'])) {
            $data['flightnum'] = $data['code'] . $data['flightnum'];
        }

        // Add pilot info
        $pilotinfo = PilotData::getPilotData($pilotid);
        $data['pilotid'] = $pilotid;
        $data['pilotname'] = $pilotinfo->firstname . ' ' . $pilotinfo->lastname;

        // Store for later
        if (isset($data['registration'])) {
            $ac_registration = $data['registration'];
            unset($data['registration']);
        }

        if (isset($data['depicao'])) {
            $dep_apt = OperationsData::GetAirportInfo($data['depicao']);
            $data['depapt'] = DB::escape($dep_apt->name);
        }

        if (isset($data['arricao'])) {
            $arr_apt = OperationsData::GetAirportInfo($data['arricao']);
            $data['arrapt'] = DB::escape($arr_apt->name);
        }

        if (isset($data['route']) && empty($data['route'])) {
            $flight_info = SchedulesData::getProperFlightNum($data['flightnum']);
            $params = array('s.code' => $flight_info['code'], 's.flightnum' => $flight_info['flightnum'], );

            $schedule = SchedulesData::findSchedules($params);
            $schedule = $schedule[0];

            $data['route'] = $schedule->route;
            //$data['route_details'] = serialize(SchedulesData::getRouteDetails($schedule->id));
        }
        //	A route was passed in, so get the details about this route   elseif (isset($data['route']) && !empty($data['route'])) {
            /*$tmp = new stdClass();
            $tmp->deplat = $dep_apt->lat;
            $tmp->deplng = $dep_apt->lng;
            $tmp->route = $data['route'];
            
            $data['route_details'] = NavData::parseRoute($tmp);
            $data['route_details'] = serialize($data['route_details']);
            unset($tmp);
        }

        if (!empty($data['route_details'])) {
            $data['route_details'] = DB::escape($data['route_details']);
        }

        if (isset($dep_apt)) {
            unset($dep_apt);
        }

        if (isset($arr_apt)) {
            unset($arr_apt);
        }

        // Clean up times
        if (isset($data['deptime']) && !is_numeric($data['deptime'])) {
            $data['deptime'] = strtotime($data['deptime']);
        }

        if (isset($data['arrtime']) && !is_numeric($data['arrtime'])) {
            $data['arrtime'] = strtotime($data['arrtime']);
        }

        /* Check the heading for the flight
        If none is specified, then point it straight to the arrival airport
        if ($data['heading'] == '' || (!isset($data['heading']) && isset($data['lat']) &&
            isset($data['lng']))) {
            /* Calculate an angle based on current coords and the
            destination coordinates

            $data['heading'] = intval(atan2(($data['lat'] - $arr_apt->lat), ($data['lng'] -
                $arr_apt->lng)) * 180 / 3.14);
            if (($data['lat'] - $data['lng']) < 0) {
                $data['heading'] += 180;
            }

            if ($data['heading'] < 0) {
                $data['heading'] += 360;
            }
        }

        if (isset($data['gs'])) {
            if ($data['gs'] == '' || empty($data['gs'])) {
                $data['gs'] = '0';
            }
        }

        // Manually add the last set
        $data['lastupdate'] = 'NOW()';

        // first see if we exist:
        $sql = 'SELECT `id`
				FROM ' . TABLE_PREFIX . "acarsdata 
				WHERE `pilotid`={$pilotid}";

        $exist = DB::get_row($sql);

        $flight_id = '';

        if ($exist) { // update
            $upd = array();
            $flight_id = $exist->id;

            foreach ($data as $field => $value) {
                $value = DB::escape(trim($value));

                // Append the message log
                if ($field === 'messagelog') {
                    $upd[] = "`messagelog`=CONCAT(`messagelog`, '{$value}')";
                } elseif ($field === 'lastupdate') {
                    $upd[] = "`lastupdate`=NOW()";
                }
                // Update times
                elseif ($field === 'deptime' || $field === 'arrtime') {
                    /*	If undefined, set a default time to now (penalty for malformed data?)
                    Won't be quite accurate....
                    if ($value == '') {
                        $value = time();
                    }

                    $upd[] = "`{$field}`=FROM_UNIXTIME({$value})";
                } else {
                    $upd[] = "`{$field}`='{$value}'";
                }
            }

            $upd = implode(',', $upd);
            $query = 'UPDATE ' . TABLE_PREFIX . "acarsdata 
					SET {$upd} 
					WHERE `id`='{$flight_id}'";

            DB::query($query);
        } else {

            // form array with $ins[column]=value and then
            //	give it to quick_insert to finish
            $ins = array();
            $vals = array();

            foreach ($data as $field => $value) {
                $ins[] = "`{$field}`";
                if ($field === 'deptime' || $field === 'arrtime') {
                    if (empty($value)) $value = time();
                    $vals[] = "FROM_UNIXTIME({$value})";
                } elseif ($field === 'lastupdate') {
                    $vals[] = 'NOW()';
                } else {
                    $value = DB::escape($value);
                    $vals[] = "'{$value}'";
                }
            }

            $ins = implode(',', $ins);
            $vals = implode(',', $vals);

            $query = 'INSERT INTO ' . TABLE_PREFIX . "acarsdata ({$ins}) 
						VALUES ({$vals})";

            DB::query($query);

            $data['deptime'] = time();
            $flight_id = DB::$insert_id;
        }

        $flight_info = self::get_flight_by_id($flight_id);

        // Add this cuz we need it
        $data['code'] = $pilotinfo->code;
        $data['pilotid'] = $pilotid;
        $data['unique_id'] = $flight_id;
        $data['aircraft'] = $flight_info->aircraftname;
        $data['registration'] = $flight_info->registration;

        // $res = CentralData::send_acars_data($data);
        return true;
    }

    public static function resetFlights() {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'acarsdata';
        DB::query($sql);

        return true;
    }

    public static function get_flight_by_id($id) {
        $id = intval($id);
        $sql = 'SELECT a.*, c.name as aircraftname, c.registration as registration,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
				FROM ' . TABLE_PREFIX . 'acarsdata a
				LEFT JOIN ' . TABLE_PREFIX . 'aircraft c ON a.`aircraft`= c.`id`
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS arr ON arr.icao = a.arricao
				LEFT JOIN ' . TABLE_PREFIX . 'pilots p ON a.`pilotid`= p.`pilotid`
				WHERE a.id=' . $id;

        return DB::get_row($sql);
    }

    public static function get_flight_by_pilot($pilotid) {
        $pilotid = intval($pilotid);
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . "acarsdata 
					WHERE `pilotid`='{$pilotid}'";

        return DB::get_row($sql);
    }

    public static function get_flight($code, $flight_num) {
        $code = DB::escape($code);
        $flight_num = DB::escape($flight_num);

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . "acarsdata 
					WHERE flightnum='{$code}{$flight_num}'";

        return DB::get_row($sql); */
    }

    /**
     * File a PIREP from an ACARS program
     *
     * @param mixed $pilotid The pilot ID of the pilot filing the PIREP
     * @param mixed $data This is the data structure with the PIREP info
     * @return bool true/false
     *
     */
    public static function FilePIREP($pilotid, $data) {
        if (!is_array($data)) {
            self::$lasterror = 'PIREP data must be array';
            return false;
        }
        $client = new Guzzle();

        $ret = $client->request('POST', VAOS_URL.'api/1_0/pireps', [
            'query' => [
                'format' => 'phpVMS'
            ],
            'form_params' => $data
        ])->getBody();

        $jdec = json_decode($ret, true);
        if ($jdec['status'] == 200)
        {
            return true;
        }
        else
        {
            return false;
        }
        /*
        # Call the pre-file event
        #
        if (CodonEvent::Dispatch('pirep_prefile', 'PIREPS', $_POST) == false) {
            return false;
        }

        # File the PIREP report
        #
        //$ret = PIREPData::FileReport($data);

        // Jump over to VAOS to file that PIREP report via phpVMS Callback

        $client = new GuzzleHttp\Client();

        $res = $client->request('POST', VAOS_URL.'api/1_0/pireps', [
            'query' => [
                'format' => 'phpVMS'
            ],
            'form_params' => [
                $data
            ]
        ])->getBody();


        # Set them as non-retired
        //PilotData::setPilotRetired($pilotid, 0);

        if (!$ret) return false;

        self::$pirepid = DB::$insert_id;

        # Call the event
        #
        CodonEvent::Dispatch('pirep_filed', 'PIREPS', $_POST);

        # Close out a bid if it exists
        #
        $bidid = SchedulesData::GetBidWithRoute($pilotid, $data['code'], $data['flightnum']);
        if ($bidid) {
            SchedulesData::RemoveBid($bidid->bidid);
        }

        return true;
        */
    }

    public static function GetAllFlights() {
        $sql = 'SELECT a.*, c.name as aircraftname, c.registration as registration,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
				FROM ' . TABLE_PREFIX . 'acarsdata a
				LEFT JOIN ' . TABLE_PREFIX . 'aircraft c ON a.`aircraft`= c.`registration`
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS arr ON arr.icao = a.arricao
				LEFT JOIN ' . TABLE_PREFIX . 'pilots p ON a.`pilotid`= p.`pilotid`';

        return DB::get_results($sql);
    }


    /**
     * This returns all of the current ACARS flights within the cutoff
     *
     * @param int $cutofftime This is the cut-off time in minutes (12 hours return in)
     * @return array Returns an array of objects with the ACARS data
     *
     */
    public static function GetACARSData($cutofftime = '') {
        //cutoff time in days
        if (empty($cutofftime)) {
            // Go from minutes to hours
            $cutofftime = Config::Get('ACARS_LIVE_TIME');
            //$cutofftime = $cutofftime / 60;
        }

        $sql = 'SELECT a.*, c.name as aircraftname, c.registration,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplng,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlng
				FROM ' . TABLE_PREFIX . 'acarsdata a
				LEFT JOIN ' . TABLE_PREFIX . 'aircraft c ON a.`aircraft`= c.`registration`
				LEFT JOIN ' . TABLE_PREFIX . 'pilots p ON a.`pilotid`= p.`pilotid`
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS arr ON arr.icao = a.arricao ';

        if ($cutofftime !== 0) {
            $sql .= 'WHERE DATE_SUB(NOW(), INTERVAL ' . $cutofftime .
                ' MINUTE) <= a.`lastupdate`';
        }

        return DB::get_results($sql);
        DB::debug();
    }
}
