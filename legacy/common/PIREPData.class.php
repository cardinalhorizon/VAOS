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

class PIREPData extends CodonData {
    
    public static $lasterror;
    public static $pirepid;


    /**
     * A generic find function for schedules. As parameters, do:
     * 
     * $params = array( 's.depicao' => 'value',
     *					's.arricao' => array ('multiple', 'values'),
     *	);
     * 
     * Syntax is ('s.columnname' => 'value'), where value can be
     *	an array is multiple values, or with a SQL wildcard (%) 
     *  if that's what is desired.
     * 
     * Columns from the schedules table should be prefixed by 's.',
     * the aircraft table as 'a.'
     * 
     * You can also pass offsets ($start and $count) in order to 
     * facilitate pagination
     * 
     * @tutorial http://docs.phpvms.net/media/development/searching_and_retriving_schedules
     */
    public static function findPIREPS($params, $count = '', $start = '') {
        
        $sql = 'SELECT p.*, UNIX_TIMESTAMP(p.submitdate) as submitdate, 
                    UNIX_TIMESTAMP(p.modifieddate) as modifieddate, 
					u.pilotid, u.firstname, u.lastname, u.email, u.rank, u.code AS pcode,
					a.id AS aircraftid, a.name as aircraft, a.registration,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplng,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlng						
				FROM ' . TABLE_PREFIX . 'pireps p
				LEFT JOIN ' . TABLE_PREFIX . 'aircraft a ON a.id = p.aircraft
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS dep ON dep.icao = p.depicao
				LEFT JOIN ' . TABLE_PREFIX . 'airports AS arr ON arr.icao = p.arricao 
				LEFT JOIN ' . TABLE_PREFIX . 'pilots u ON u.pilotid = p.pilotid ';

        /* Build the select "WHERE" based on the columns passed */
        $sql .= DB::build_where($params);

        if (Config::Get('PIREPS_ORDER_BY') != '') {
            $sql .= ' ORDER BY ' . Config::Get('PIREPS_ORDER_BY');
        }

        if (strlen($count) != 0) {
            $sql .= ' LIMIT ' . $count;
        }

        if (strlen($start) != 0) {
            $sql .= ' OFFSET ' . $start;
        }

        return DB::get_results($sql);
    }

    /**
     * Get internal data for the past $interval months, including the
     * total number of PIREPS and revenue
     *
     * @param array $where_params Any specific conditions to search on
     * @param int $interval The interval, in months
     * @return mixed This is the return value description
     *
     */
    public static function getIntervalDataByMonth($where_params, $interval = '6') {
        
        $date_clause = "DATE_SUB(NOW(), INTERVAL {$interval} MONTH) <= p.submitdate";

        /* See if this array already exists */
        if (!is_array($where_params)) {
            $where_params = array($date_clause);
        } else {
            $where_params[] = $date_clause;
        }

        $data = self::getIntervalData($where_params);

        if (!$data) {
            return array();
        }

        foreach ($data as $month) {
            $month = FinanceData::calculateFinances($month);
        }

        return $data;
    }

    /**
     * Get internal data for the past $interval days, including the
     * total number of PIREPS and revenue
     *
     * @param array $where_params Any specific conditions to search on
     * @param int $interval The interval, in days
     * @return mixed This is the return value description
     *
     */
    public static function getIntervalDataByDays($where_params, $interval = '7') {
        
        $date_clause = "DATE_SUB(CURDATE(), INTERVAL {$interval} DAY)  <= p.submitdate";

        /* See if this array already exists */
        if (!is_array($where_params)) {
            $where_params = array($date_clause);
        } else {
            $where_params[] = $date_clause;
        }

        return self::getIntervalData($where_params, 'D');
    }


    /**
     * Get interval financial data, with the date clause
     * passed in as a WHERE:
     * 
     * "DATE_SUB(CURDATE(), INTERVAL {$interval} DAY)  <= p.submitdate";
     * 
     * Or some form of a date limitation, but has to be within the
     * where clause
     *
     * @param array $where_params Any WHERE parameters
     * @param char $grouping How to group data - Y for yearly, M for monthly, D for daily
     * @return array Returns finance data according to the above grouping
     *
     */
    public static function getIntervalData($where_params, $grouping = 'M') {
        
        $grouping = strtolower($grouping);

        if ($grouping == 'y') {
            $format = '%Y';
        } elseif ($grouping == 'm') {
            $format = '%Y-%m';
        } elseif ($grouping == 'd') /* go by day */ {
            $format = '%Y-%m-%d';
        }

        $sql = "SELECT DATE_FORMAT(p.submitdate, '{$format}') AS ym,
					UNIX_TIMESTAMP(p.submitdate) AS timestamp,
					COUNT(p.pirepid) AS total,
					SUM(p.revenue) as revenue,
					SUM(p.gross) as gross,
					SUM(p.fuelprice) as fuelprice,
					SUM(p.price) as price,
					SUM(p.expenses) as expenses,
					(SELECT SUM(`amount`) 
                        FROM `".TABLE_PREFIX."ledger`
                        WHERE DATE_FORMAT(submitdate, '{$format}') 
                            AND `paysource` = ".PAYSOURCE_PIREP."
                    ) AS `pilotpay`
				FROM " . TABLE_PREFIX . "pireps p";

        $sql .= DB::build_where($where_params);
        $sql .= ' GROUP BY `ym` ORDER BY `timestamp` ASC';

        $results = DB::get_results($sql);

        return $results;
    }

    /**
     * Return all of the pilot reports. Can pass a start and
     * count for pagination. Returns 20 rows by default. If you
     * only want to return the latest n number of reports, use
     * getRecentReportsByCount()
     * 
     * @param int $count Number of PIREPS to return
     * @param int $start The record # to start from (for pagination)
     * @return array List of PIREPs
     */
    public static function getAllReports($count = '', $start = 0) {
        return self::findPIREPS(array(), $count, $start);
    }

    /**
     * Get all of the reports by the accepted status. Use the constants:
     * PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED,PIREP_INPROGRESS
     * 
     * @param int $accepted 
     */
    public static function getAllReportsByAccept($accepted = 0) {
        return self::findPIREPS(array('p.accepted' => $accept));
    }

    /**
     * PIREPData::getAllReportsFromHub()
     * 
     * @param string $hub
     * @param integer $accepted
     * @return array All of the PIREPs for this hub
     */
    public static function getAllReportsFromHub($hub, $accepted = 0) {
        return self::findPIREPS(array('p.accepted' => $accepted, 'u.hub' => $hub));
    }

    /**
     * Get the latest reports that have been submitted,
     * return the last 10 by default
     * 
     * @param int $count Number of PIREPs to return
     */
    public static function getRecentReportsByCount($count = 10) {
        
        if ($count == '') {
            $count = 10;
        }

        return self::findPIREPS(array(), intval($count));
    }

    /**
     * Get the latest reports by n number of days
     * 
     * @param int $days Number of days to return
     */
    public static function getRecentReports($days = 2) {
        return self::findPIREPS(array(
            'DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY) <= p.submitdate')
        );
    }

    /**
     * Get all of the reports by the exported status (true or false)
     */
    public static function getReportsByExportStatus($status) {
        if ($status === true) $status = 1;
        else  $status = 0;

        return self::findPIREPS(array('p.exported' => $status));
    }

    /**
     * Get the number of reports on a certain date
     *  Pass unix timestamp for the date
     */
    public static function getReportCount($date) {
        
        $sql = 'SELECT COUNT(*) AS count 
				FROM ' . TABLE_PREFIX . 'pireps
				WHERE DATE(submitdate)=DATE(FROM_UNIXTIME(' . $date . '))';

        $row = DB::get_row($sql);
        if (!$row) return 0;

        return ($row->count == '') ? 0 : $row->count;
    }

    /**
     * Get the number of reports on a certain date, for a certain route
     * 
     * @param string $code Airline code
     * @param string $flightnum Flight number
     * @param timestamp $date UNIX timestamp
     */
    public static function getReportCountForRoute($code, $flightnum, $date) {
        $MonthYear = date('mY', $date);
        $sql = "SELECT COUNT(*) AS count 
				FROM " . TABLE_PREFIX . "pireps
				WHERE DATE_FORMAT(submitdate, '%c%Y') = '$MonthYear'
					AND code='$code' AND flightnum='$flightnum'";

        $row = DB::get_row($sql);
        return $row->count;
    }

    /**
     * Get the number of reports for the last x  number of days
     * Returns 1 row for every day, with the total number of
     * reports per day
     */
    public static function getCountsForDays($days = 7) {
        
        $sql = 'SELECT DISTINCT(DATE(submitdate)) AS submitdate,
					(SELECT COUNT(*) FROM '.TABLE_PREFIX.'pireps 
                        WHERE DATE(submitdate)=DATE(p.submitdate)) AS count
				FROM '.TABLE_PREFIX.'pireps p 
                WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= p.submitdate';

        return DB::get_results($sql);
    }

    /**
     * Get all of the details for a PIREP, including lat/long of the airports
     */
    public static function getReportDetails($pirepid) {
        
        $sql = 'SELECT p.*, s.*, s.id AS scheduleid, p.route, p.route_details,
					u.pilotid, u.firstname, u.lastname, u.email, u.rank, u.code AS pcode,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplng,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlng,
				    p.code, p.flightnum, p.depicao, p.arricao,  p.price AS price,
				    a.id as aircraftid, a.name as aircraft, a.registration, p.flighttime,
				    p.distance, 
                    UNIX_TIMESTAMP(p.submitdate) as submitdate, 
                    UNIX_TIMESTAMP(p.modifieddate) as modifieddate,
                    p.accepted, p.log
				FROM '.TABLE_PREFIX.'pilots u, '.TABLE_PREFIX.'pireps p
					LEFT JOIN ' . TABLE_PREFIX . 'airports AS dep ON dep.icao = p.depicao
					LEFT JOIN ' . TABLE_PREFIX . 'airports AS arr ON arr.icao = p.arricao
					LEFT JOIN ' . TABLE_PREFIX . 'aircraft a ON a.id = p.aircraft
					LEFT JOIN ' . TABLE_PREFIX . 'schedules s ON s.code = p.code AND s.flightnum = p.flightnum
				WHERE p.pilotid=u.pilotid AND p.pirepid=' . $pirepid;

        $row = DB::get_row($sql);
        $row->rawdata = unserialize($row->rawdata);

        /* Do any specific replacements here */
        if ($row) {
            
            /* If it's FSFlightKeeper, process the `rawdata` column, which contains
                array()'d copied of extra data that was sent by the ACARS. Run that
                through some templates which we've got. This can probably be generic-ized
                but it's fine now for FSFK. This can probably move through an outside 
                function, but seems OK to stay in getReportDetails() for now, since this
                is semi-intensive code here (the most expensive is populating the templates,
                and I wouldn't want to run it for EVERY PIREP which is called by the system.
                */
            if ($row->source == 'fsfk') {
                /* Do data stuff in the logs */
                $data = $row->rawdata;

                /* Process flight data */
                if (isset($data['FLIGHTDATA'])) {
                    Template::Set('data', $data['FLIGHTDATA']);
                    $flightdata = Template::Get('fsfk_log_flightdata.tpl', true, true, true);
                    $row->log .= $flightdata;
                    unset($flightdata);
                }

                /* Process the flightplan */
                if (isset($data['FLIGHTPLAN'])) {
                    $value = trim($data['FLIGHTPLAN']);
                    $lines = explode("\n", $value);

                    Template::Set('lines', $lines);
                    $flightplan = Template::Get('fsfk_log_flightplan.tpl', true, true, true);

                    $row->log .= $flightplan;
                    unset($flightplan);
                }

                /* Process flight critique data */
                if (isset($data['FLIGHTCRITIQUE'])) {
                    $value = $data['FLIGHTCRITIQUE'];
                    $value = trim($value);
                    preg_match_all("/(.*) \| (.*)\n/", $value, $matches);

                    # Get these from a template
                    Template::Set('matches', $matches);
                    $critique = Template::Get('fsfk_log_flightcritique.tpl', true, true, true);

                    $row->log .= $critique;
                    unset($critique);
                }

                /* Process the flight images, last */
                if (isset($data['FLIGHTMAPS'])) {
                    Template::Set('images', $data['FLIGHTMAPS']);
                    $flightimages = Template::Get('fsfk_log_flightimages.tpl', true, true, true);
                    $row->log .= $flightimages;
                    unset($flightimages);
                }
            }
            /* End "if FSFK" */


            if ($row->route_details != '') {
                $row->route_details = unserialize($row->route_details);
            } else {
                $row->route_details = NavData::parseRoute($row);
            }

        }
        /* End "if $row" */

        return $row;
    }


    /**
     * Get the latest reports for a pilot
     * 
     * @param int $pilotid Pilot ID to retrieve last reports for
     * @param int $count Number of reports to return
     * @param string $status Status type to return (only accepted, etc) 
     * @return
     */
    public static function getLastReports($pilotid, $count = 1, $status = '') {
        
        if($pilotid == '') {
            return false;
        }
        
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pireps
					WHERE pilotid=' . intval($pilotid);

        # Check it via the status
        if ($status != '') {
            $sql .= ' AND accepted=' . intval($status);
        }

        $sql .= ' ORDER BY submitdate DESC
					LIMIT ' . intval($count);

        if ($count == 1) return DB::get_row($sql);
        else  return DB::get_results($sql);
    }

    /**
     * Get all of a pilot's reports by status
     * 
     * @param int $pilotid The Pilot ID to find
     * @param integer $accept PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED, PIREP_INPROGRESS
     * @return
     */
    public static function getReportsByAcceptStatus($pilotid, $accept = 0) {
        return self::findPIREPS(array(
            'p.pilotid' => intval($pilotid), 
            'p.accepted' => intval($accept)
            )
        );
    }

    /**
     * Get the count of comments
     */
    public static function getCommentCount($pirepid) {
        
        $sql = 'SELECT COUNT(*) AS total FROM '.TABLE_PREFIX.'pirepcomments
					WHERE pirepid=' . $pirepid . '
					GROUP BY pirepid';

        $total = DB::get_row($sql);

        if ($total == '') {
            return 0;
        }

        return $total->total;
    }

    /**
     * Set the export status of PIREPs (vaCentral)
     * 
     * @param bool $status
     * @return
     */
    public static function setAllExportStatus($status = true) {
        
        if ($status === true) {
            $status = 1;
        } else {
            $status = 0;
        }

        $sql = 'UPDATE '.TABLE_PREFIX.'pireps SET `exported`='.$status;

        $res = DB::query($sql);

        if (DB::errno() != 0) {
            return false;
        }

        return true;
    }

    /**
     * Set the exported status of a specific PIREP
     * 
     * @param int $pirepid
     * @param bool $status
     * @return
     */
    public static function setExportedStatus($pirepid, $status) {
        
        if ($status === true) {
            $status = 1;
        } else {
            $status = 0;
        }

        return self::editPIREPFields($pirepid, array('exported' => $status));
    }


    /**
     * Get all of the comments for a pilot report
     */
    public static function getComments($pirepid) {
        
        $sql = 'SELECT c.*, UNIX_TIMESTAMP(c.postdate) as postdate,
						p.firstname, p.lastname
					FROM ' . TABLE_PREFIX . 'pirepcomments c, ' . TABLE_PREFIX . 'pilots p
					WHERE p.pilotid=c.pilotid AND c.pirepid=' . $pirepid . '
					ORDER BY postdate ASC';

        return DB::get_results($sql);
    }

    public static function deleteComment($comment_id) {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'pirepcomments WHERE `id`=' . $comment_id;
        $res = DB::query($sql);

        if (DB::errno() != 0) {
            return false;
        }

        return true;
    }

    
    /**
     * PIREPData::fileReport()
     * 
     * @param mixed $pirepdata
     * @return
     */
    public static function fileReport($pirepdata) {

        /*$pirepdata = array('pilotid'=>'',
            'code'=>'',
            'flightnum'=>'',
            'depicao'=>'',
            'arricao'=>'',
            'aircraft'=>'',
            'flighttime'=>'',
            'submitdate'=>'',
            'comment'=>'',
            'fuelused'=>'',
            'source'=>''
            'log'=>''
            );*/

        if (!is_array($pirepdata)) return false;

        $pirepdata['code'] = strtoupper($pirepdata['code']);
        $pirepdata['flightnum'] = strtoupper($pirepdata['flightnum']);
        $pirepdata['depicao'] = strtoupper($pirepdata['depicao']);
        $pirepdata['arricao'] = strtoupper($pirepdata['arricao']);

        /* Check if this PIREP was just submitted, check the last 10 minutes
        */
        if (Config::Get('PIREP_CHECK_DUPLICATE') == true) {
            $time_limit = Config::Get('PIREP_TIME_CHECK');
            if (empty($time_limit)) {
                $time_limit = 1;
            }

            $sql = "SELECT `pirepid` FROM " . TABLE_PREFIX . "pireps
					WHERE `pilotid` = {$pirepdata['pilotid']} 
						AND `code` = '{$pirepdata['code']}'
						AND `flightnum` = '{$pirepdata['flightnum']}' 
						AND DATE_SUB(NOW(), INTERVAL {$time_limit} MINUTE) <= `submitdate`";

            $res = DB::get_row($sql);

            if ($res) {
                self::$lasterror = 'This PIREP was just submitted!';
                return $res->pirepid;
            }
        }

        if ($pirepdata['depicao'] == '' || $pirepdata['arricao'] == '') {
            self::$lasterror = 'The departure or arrival airports are blank';
            return false;
        }

        # Check the aircraft
        if (!is_numeric($pirepdata['aircraft'])) {
            // Check by registration
            $ac = OperationsData::getAircraftByReg($pirepdata['aircraft']);
            if ($ac) {
                $pirepdata['aircraft'] = $ac->id;
            } else {
                // Check by name
                $ac = OperationsData::getAircraftByName($pirepdata['aircraft']);
                if ($ac) {
                    $pirepdata['aircraft'] = $ac->id;
                } else {
                    $pirepdata['aircraft'] = '0';
                }
            }
        }

        # Check the airports, add to database if they don't exist
        $depapt = OperationsData::getAirportInfo($pirepdata['depicao']);
        if (!$depapt) {
            $depapt = OperationsData::RetrieveAirportInfo($pirepdata['depicao']);
        }

        $arrapt = OperationsData::getAirportInfo($pirepdata['arricao']);
        if (!$arrapt) {
            $arrapt = OperationsData::RetrieveAirportInfo($pirepdata['arricao']);
        }

        # Look up the schedule
        $sched = SchedulesData::getScheduleByFlight($pirepdata['code'], $pirepdata['flightnum']);

        /*	Get route information, and also the detailed layout of the route
        Store it cached, in case the schedule changes later, then the route
        information remains intact. Also, if the nav data changes, then 
        the route is saved as it was 
        */

        if (!empty($pirepdata['route'])) {
            /*	They supplied some route information, so build up the data
            based on that. It needs a certain structure passed, so build that */

            $pirepdata['route'] = str_replace('SID', '', $pirepdata['route']);
            $pirepdata['route'] = str_replace('STAR', '', $pirepdata['route']);
            $pirepdata['route'] = str_replace('DCT', '', $pirepdata['route']);
            $pirepdata['route'] = trim($pirepdata['route']);

            $tmp = new stdClass();
            $tmp->deplat = $depapt->lat;
            $tmp->deplng = $depapt->lng;
            $tmp->route = $pirepdata['route'];

            $pirepdata['route_details'] = NavData::parseRoute($tmp);
            $pirepdata['route_details'] = serialize($pirepdata['route_details']);
            unset($tmp);
        }

        if (empty($pirepdata['route']) && !empty($sched->route)) {
            $pirepdata['route'] = $sched->route;
            $pirepdata['route'] = str_replace('SID', '', $pirepdata['route']);
            $pirepdata['route'] = str_replace('STAR', '', $pirepdata['route']);
            $pirepdata['route'] = str_replace('DCT', '', $pirepdata['route']);
            $pirepdata['route'] = trim($pirepdata['route']);

            /*	The schedule doesn't have any route_details, so let's populate
                the schedule while we're here. Then we'll use that same info
                to populate our details information 
             */
            if (empty($sched->route_details)) {
                $pirepdata['route_details'] = serialize(SchedulesData::getRouteDetails($sched->id));
            } else {
                /*	The schedule does have route information, and it's already been cached */
                $pirepdata['route_details'] = $sched->route_details;
            }
        }

        /*	This setting forces the next code to automatically
        calculate a load value for this current PIREP */
        if (Config::Get('PIREP_OVERRIDE_LOAD') == true) {
            $pirepdata['load'] == '';
        }

        # Check the load, if it's blank then look it up
        #	Based on the aircraft that was flown
        if (!isset($pirepdata['load']) || empty($pirepdata['load'])) {
            $pirepdata['load'] = FinanceData::getLoadCount($pirepdata['aircraft'], $sched->flighttype);
        }

        /* If the distance isn't supplied, then calculate it */
        if (!isset($pirepdata['distance']) || empty($pirepdata['distance'])) {
            $pirepdata['distance'] = OperationsData::getAirportDistance($depapt, $arrapt);
        }

        /* See if there's a landing rate */
        if (!isset($pirepdata['landingrate']) || empty($pirepdata['landingrate'])) {
            $pirepdata['landingrate'] = 0;
        }

        /* Any "raw" parameterized data which needs to be added */
        if (isset($pirepdata['rawdata'])) {
            $pirepdata['rawdata'] = serialize($pirepdata['rawdata']);
        } else {
            $pirepdata['rawdata'] = '';
        }

        /* Escape the comment field */
        //$pirepdata['log'] = DB::escape($pirepdata['log']);
        if (isset($pirepdata['comment'])) {
            $comment = DB::escape($pirepdata['comment']);
            unset($pirepdata['comment']);
        }

        /* Proper timestamp */
        $pirepdata['flighttime'] = str_replace(':', '.', $pirepdata['flighttime']);
        $pirepdata['flighttime_stamp'] = str_replace('.', ':', $pirepdata['flighttime']).':00';

        /* Export status as 0 */
        $pirepdata['exported'] = 0;
        $pirepdata['submitdate'] = 'NOW()';
        $pirepdata['modifieddate'] = 'NOW()';
        $pirepdata['accepted'] = PIREP_PENDING;
        $pirepdata['expenselist'] = '0';
        $pirepdata['flighttype'] = $sched->flighttype;

        # Do the insert based on the columns here
        $cols = array();
        $col_values = array();
        foreach ($pirepdata as $key => $value) {
            if($key == 'submitdate') {
                $value = 'NOW()';
            } elseif ($key == 'comment') {
                continue;
            } else {
                $value = "'".DB::escape($value)."'";
            }
            
            $cols[] = "`{$key}`";        
            $col_values[] = $value;
        }

        $cols = implode(', ', $cols);
        $col_values = implode(', ', $col_values);
        $sql = 'INSERT INTO ' . TABLE_PREFIX . "pireps ({$cols}) VALUES ({$col_values});";

        DB::query($sql);
        $pirepid = DB::$insert_id;

        // Add the comment if its not blank
        if ($comment != '') {
            self::addComment($pirepid, $pirepdata['pilotid'], $comment);
        }

        # Update the financial information for the PIREP, true to refresh fuel
        self::PopulatePIREPFinance($pirepid, true);

        # Do other assorted tasks that are along with a PIREP filing
        # Update the flown count for that route
        self::UpdatePIREPFeed();

        # Update any pilot's information
        $pilotinfo = PilotData::getPilotData($pirepdata['pilotid']);
        $pilotcode = PilotData::getPilotCode($pilotinfo->code, $pilotinfo->pilotid);
        PilotData::UpdateLastPIREPDate($pilotinfo->pilotid);
        /*
        if (Config::Get('EMAIL_SEND_PIREP') === true) {
            
            # Send an email to the admin that a PIREP was submitted
            $sub = "A PIREP has been submitted by {$pilotcode} ({$pirepdata['depicao']} - {$pirepdata['arricao']})";
            $message = "A PIREP has been submitted by {$pilotcode} "
                ."({$pilotinfo->firstname} {$pilotinfo->lastname})\n\n"
                ."{$pirepdata['code']}{$pirepdata['flightnum']}: {$pirepdata['depicao']} to {$pirepdata['arricao']}\n"
                ."Aircraft: {$pirepdata['aircraft']}\n" . "Flight Time: {$pirepdata['flighttime']}\n"
                ."Landing Rate: {$pirepdata['landingrate']}\n"."Filed using: {$pirepdata['source']}\n\n" 
                ."Comment: {$comment}\n\n"
                ."Click to approve this pirep (admin must be signed in):\n"
                .adminurl('/pirepadmin/approvepirep/'.$pirepid);

            $email = Config::Get('EMAIL_NEW_PIREP');
            if (empty($email)) {
                $email = ADMIN_EMAIL;
            }

            Util::SendEmail($email, $sub, $message);

        }
        */
        /* Add this into the activity feed */
        /*
        $message = Lang::get('activity.new.pirep');
        foreach($pirepdata as $key=>$value) {
            $message = str_replace('$'.$key, $value, $message);
        }
        
        # Add it to the activity feed
        ActivityData::addActivity(array(
            'pilotid' => $pirepdata['pilotid'],
            'type' => ACTIVITY_NEW_PIREP,
            'refid' => $pirepid,
            'message' => htmlentities($message),
        ));

        /* Now send data to vaCentral */
        /*
        CentralData::send_pirep($pirepid);

        // Reset this ID back
        DB::$insert_id = $pirepid;
        self::$pirepid = $pirepid;
        return $pirepid;
        */
    }

    /**
     * Update a specific PIREP
     * 
     * @param int $pirepid ID of PIREP to update
     * @param array $pirepdata Dictionary array of fields to update
     * @param bool $recalc_finances Recalculate finances or not (fields must be passed!)
     * @return
     */
    public static function updateFlightReport($pirepid, $pirepdata, $recalc_finances = true) {

        if (!is_array($pirepdata)) {
            return false;
        }

        if ($pirepdata['depicao'] == '' || $pirepdata['arricao'] == '') {
            return false;
        }

        $pirepinfo = self::getReportDetails($pirepid);

        if(isset($pirepdata['fuelused']) && isset($pirepdata['fuelunitcost'])) {
            $pirepdata['fuelprice'] = $pirepdata['fuelused'] * $pirepdata['fuelunitcost'];
        }

        if(isset($pirepdata['flighttime'])) {
            $flighttime_stamp = str_replace('.', ':', $pirepdata['flighttime']) . ':00';
            $pirepdata['flighttime'] = str_replace(':', ',', $pirepdata['flighttime']);
        }

        # Recalculate finances if these fields are set...
        if($recalc_finances === true) {
         
            $data = array(
                'price' => $pirepdata['price'], 
                'load' => $pirepdata['load'],
                'expenses' => $pirepdata['expenses'], 
                'fuelprice' => $pirepdata['fuelprice'],
                'pilotpay' => $pirepdata['pilotpay'], 
                'flighttime' => $pirepdata['flighttime'], 
            );
    
            $gross = floatval($pirepdata['load']) * floatval($pirepdata['price']);
            $revenue = self::getPIREPRevenue($data, $pirepinfo->paytype);

            $pirepdata = array_merge($pirepdata, array(
                'flighttime_stamp' => $flighttime_stamp,
                'gross' => $gross,
                'revenue' => $revenue,
                )
            );
        }
        
        $pirepdata['modifieddate'] = 'NOW()';
        
        $ret = self::editPIREPFields($pirepid, $pirepdata);
        
        self::calculatePIREPPayment($pirepid);
        
        return $ret;
    }

    /**
     * Update any fields in a PIREP, other update functions come down to this
     *
     * @param int $pirepid ID of the PIREP to update
     * @param array $fields Array, column name as key, with values to update
     * @return bool 
     *
     */
    public static function updatePIREPFields($pirepid, $fields) {
        return self::editPIREPFields($pirepid, $fields);
    }

    /**
     * Update any fields in a PIREP, other update functions come down to this
     *
     * @param int $pirepid ID of the PIREP to update
     * @param array $fields Array, column name as key, with values to update
     * @return bool 
     *
     */
    public static function editPIREPFields($pirepid, $fields) {
        
        if (!is_array($fields)) {
            return false;
        }

        $fields['modifieddate'] = 'NOW()';
        
        $sql = "UPDATE `" . TABLE_PREFIX . "pireps` SET ";
        $sql .= DB::build_update($fields);
        $sql .= ' WHERE `pirepid`=' . $pirepid;

        $res = DB::query($sql);

        if (DB::errno() != 0) {
            return false;
        }

        return true;
    }

    /**
     * Populate PIREPS which have 0 values for the load/price, etc
     * 
     */
    public static function populateEmptyPIREPS() {
        
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pireps ';

        $results = DB::get_results($sql);
        if (!$results) {
            return true;
        }

        foreach ($results as $row) {
            self::PopulatePIREPFinance($row, true);
        }

        return true;
    }

    /**
     * Populate the PIREP with the fianancial info needed
     * 
     * @param mixed $pirep Either a PIREP ID or the row
     * @param bool $reset_fuel Reset the fuel costs or not?
     * @return
     */
    public static function populatePIREPFinance($pirep, $reset_fuel = false) {
        
        if (!is_object($pirep) && is_numeric($pirep)) {
            $pirep = PIREPData::getReportDetails($pirep);
            if (!$pirep) {
                self::$lasterror = 'PIREP does not exist';
                return false;
            }
        }

        # Set the PIREP ID
        $pirepid = $pirep->pirepid;
        $sched = SchedulesData::getScheduleByFlight($pirep->code, $pirep->flightnum, '');
        if (!$sched) {
            self::$lasterror = 'Schedule does not exist. Please update this manually.';
            return false;
        }

        $pilot = PilotData::getPilotData($pirep->pilotid);

        # Get the load factor for this flight
        if ($pirep->load == '' || $pirep->load == 0) {
            $pirep->load = FinanceData::getLoadCount($pirep->aircraft, $sched->flighttype);
        }

        // Fix for bug #62, check the airport fuel price as 0 for live
        //$depapt = OperationsData::getAirportInfo($pirep->depicao);
        if ($pirep->fuelunitcost == '' || $pirep->fuelunitcost == 0 || $reset_fuel == true) {
            $pirep->fuelunitcost = FuelData::getFuelPrice($pirep->depicao);
        }

        # Check the fuel
        if ($pirep->fuelprice != '' || $reset_fuel == true) {
            $pirep->fuelprice = FinanceData::getFuelPrice($pirep->fuelused, $pirep->depicao);
        }

        # Get the expenses for a flight
        $total_ex = 0;
        $expense_list = '';

        /* Account for any fixed-cost percentages */
        $allexpenses = FinanceData::getFlightExpenses();
        if (is_array($allexpenses)) {
            foreach ($allexpenses as $ex) {
                $total_ex += $ex->cost;
            }
        }

        /* Account for any per-flight %age expenses */
        $all_percent_expenses = FinanceData::getFlightPercentExpenses();
        $gross = floatval($sched->price) * floatval($pirep->load);
        if (is_array($all_percent_expenses)) {
            foreach ($all_percent_expenses as $ex) {
                $cost = str_replace('%', '', $ex->cost);
                $percent = $cost / 100;
                $total = ($gross * $percent);

                $total_ex += $total;
            }
        }
        
        /*  Set the pilotpay here - if it was a per-schedule payment,
            then set the pilot pay to that, otherwise, set it to the
            total amount paid... */
        # Handle pilot pay
        if(!empty($sched->payforflight)) {
            $pilot->payrate = $sched->payforflight;
            $payment_type = PILOT_PAY_SCHEDULE;
        } else {
            $payment_type = PILOT_PAY_HOURLY;
        }
        
        $data = array(
            'price' => $sched->price, 
            'load' => $pirep->load, 
            'fuelprice' => $pirep->fuelprice, 
            'expenses' => $total_ex, 
            'pilotpay' => $pilot->payrate, 
            'flighttime' =>$pirep->flighttime, 
            );

        $revenue = self::getPIREPRevenue($data, $payment_type);

        /* Now update the PIREP */
        $fields = array(
            'price' => $sched->price, 
            'load' => $pirep->load, 
            'gross' => $gross,
            'fuelprice' => $pirep->fuelprice, 
            'fuelunitcost' => $pirep->fuelunitcost,
            'expenses' => $total_ex, 
            'pilotpay' => $pilot->payrate,
            'paytype' => $payment_type, 
            'revenue' => $revenue
            );

        if (isset($data['load']) && $data['load'] != '') {
            $fields['load'] = $data['load'];
        }

        return self::editPIREPFields($pirepid, $fields);
    }

    /**
     * Calculate the gross revenue of a PIREP
     * 
     * @param array $data Associative array with price, load, pilotpay, flighttime
     * @param int $payment_type 1 for hourly payment, 2 for per-schedule payment
     * @return
     */
    public static function getPIREPRevenue($data, $payment_type = PILOT_PAY_HOURLY) {
        
        $gross = $data['price'] * $data['load'];
        
        if($payment_type == PILOT_PAY_HOURLY) {
            $pilotpay = $data['pilotpay'] * $data['flighttime'];   
        } else {
            $pilotpay = $data['pilotpay'];
        }

        if ($data['expenses'] == '') {
            $data['expenses'] = 0;
        }

        $revenue = $gross - $data['expenses'] - $data['fuelprice'] - $pilotpay;

        return $revenue;
    }

    /**
     * Delete a flight report and all of its associated data
     * Alias to PIREPData::deleteFlightReport()
     * 
     * @param int $pirepid Delete a PIREP
     * @return none
     */
    public static function deletePIREP($pirepid) {
        self::deleteFlightReport($pirepid);
    }

    /**
     * Delete a flight report and all of its associated data
     * 
     * @param int $pirepid Delete a PIREP
     * @return none
     */
    public static function deleteFlightReport($pirepid) {
        
        $pirepid = intval($pirepid);
        $pirep_details = self::getReportDetails($pirepid);
        
        $delete_tables = array(
            'pireps', 'pirepcomments', 'pirepvalues', 'ledger'
        );
        
        foreach($delete_tables as $table) {
            DB::query(
                'DELETE FROM '.TABLE_PREFIX.$table.' 
                 WHERE `pirepid`='.intval($pirepid)
            );
        }

        PilotData::resetPilotPay($pirep_details->pilotid);
        PilotData::updatePilotStats($pirep_details->pilotid);

        self::UpdatePIREPFeed();
    }

    /**
     * PIREPData::deleteAllRouteDetails()
     * 
     * @return
     */
    public static function deleteAllRouteDetails() {
        
        $row = DB::get_row("UPDATE ".TABLE_PREFIX."pireps SET `route_details` = ''");

        if (!$row) {
            return false;
        }

        return true;
    }

    /**
     * PIREPData::updatePIREPFeed()
     * 
     * @return
     */
    public static function updatePIREPFeed() {
        
        # Load PIREP into RSS feed
        $reports = PIREPData::findPIREPS(array(), 10);
        
        # Empty the rss file if there are no pireps
        if (!$reports) {
            return false;
        }

        $rss = new RSSFeed('Latest Pilot Reports', SITE_URL, 'The latest pilot reports');
        foreach ($reports as $report) {
            $rss->AddItem('Report #' . $report->pirepid . ' - ' . $report->depicao . ' to ' .
                $report->arricao, SITE_URL . '/admin/index.php?admin=viewpending', '',
                'Filed by ' . PilotData::getPilotCode($report->code, $report->pilotid) . " ($report->firstname $report->lastname)");
        }

        $rss->BuildFeed(LIB_PATH . '/rss/latestpireps.rss');
    }
    
    /**
     * Return true if a PIREP if under $age_hours old	
     *
     * @param int $pirepid PIREP ID
     * @param int $age_hours The age in hours to see if a PIREP is under
     * @return bool True/false
     *
     */
    public static function isPIREPUnderAge($pirepid, $age_hours) {
        
        $pirepid = intval($pirepid);

        $sql = "SELECT pirepid
				FROM " . TABLE_PREFIX . "pireps
				WHERE DATE_SUB(CURDATE(), INTERVAL {$age_hours} HOUR) <= submitdate
					AND pirepid={$pirepid}";

        $row = DB::get_row($sql);

        if (!$row) {
            return false;
        }

        return true;        
    }

    /**
     * Append to a flight report's log
     */

    public static function appendToLog($pirepid, $log) {
        
        $sql = 'UPDATE ' . TABLE_PREFIX . 'pireps 
					SET `log` = CONCAT(`log`, \'' . $log . '\')
					WHERE `pirepid`=' . $pirepid;

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Get all of the reports for a pilot. Pass the pilot id
     * The ID is their database ID number, not their airline ID number
     * 
     * @deprecated Use findPIREPS() instead
     */
    public static function getAllReportsForPilot($pilotid) {
        return self::findPIREPS(array('p.pilotid' => $pilotid));
    }

    /**
     * Change the status of a PIREP. For the status, use the constants:
     * PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED,PIREP_INPROGRESS
     * 
     * Also handle paying the pilot, and handle PIREP rejection, etc
     * 
     * @param int $pirepid The PIREP ID of status to change
     * @param int $status Use consts: PIREP_PENDING, PIREP_ACCEPTED, PIREP_REJECTED,PIREP_INPROGRESS
     * @return bool
     */
    public static function changePIREPStatus($pirepid, $status) {
        
        # Look up the status of the PIREP of previous
        $pirep_details = PIREPData::getReportDetails($pirepid);
        
        if(!$pirep_details) {
            return false;
        }
        
        if($pirep_details->accepted == $status) {
            return true;
        }
        
        $ret = self::editPIREPFields($pirepid, array('accepted' => $status));
        
        # Do something if the PIREP was previously marked as pending
        if($pirep_details->accepted == PIREP_PENDING) {
            
            if($status == PIREP_ACCEPTED) {
                self::calculatePIREPPayment($pirepid);       
                SchedulesData::changeFlownCount($pirep_details->code, $pirep_details->flightnum, '+1');
                
            } elseif($status == PIREP_REJECTED) {
                // Do nothing, since nothing in the PIREP was actually counted                
            }
            
        } elseif($pirep_details->accepted == PIREP_ACCEPTED) { # If already accepted
        
            if($status == PIREP_REJECTED) {
                LedgerData::deletePaymentByPIREP($pirep_details->pirepid);
                PilotData::resetPilotPay($pirep_details->pilotpay);
                SchedulesData::changeFlownCount($pirep_details->code, $pirep_details->flightnum, '-1');
            }
        }
        
        PilotData::updatePilotStats($pirep_details->pilotid);
        RanksData::calculateUpdatePilotRank($pirep_details->pilotid);
        PilotData::generateSignature($pirep_details->pilotid);
        StatsData::updateTotalHours();
        
        return $ret;
    }
    
    /**
     * Add a payment for a PIREP.
     * 
     * @param int $pirepid PIREP ID
     * @return
     */
    public static function calculatePIREPPayment($pirepid) {
        
        $pirep = DB::get_row(
            'SELECT `pirepid`, `pilotid`, 
                    `flighttime_stamp`, `pilotpay`, 
                `paytype`, `flighttype`, `accepted`
            FROM `'.TABLE_PREFIX.'pireps`
            WHERE `pirepid`='.$pirepid        
        );
            
        if($pirep->accepted == PIREP_REJECTED) {
            return false;
        }
                
        if($pirep->paytype == PILOT_PAY_HOURLY) {
            # Price out per-hour?
            $peices = explode(':', $pirep->flighttime_stamp);
            $minutes = ($peices[0] * 60) + $peices[1];
            $amount = $minutes * ($pirep->pilotpay / 60);
            
        } elseif($pirep->paytype == PILOT_PAY_SCHEDULE) {
            $amount = $pirep->pilotpay;
        }
        
        $params = array(
            'pirepid' => $pirepid,
            'pilotid' => $pirep->pilotid,
            'paysource' => PAYSOURCE_PIREP,
            'paytype' => $pirep->paytype,
            'amount' => $amount,
        );
        
        $entry = LedgerData::getPaymentByPIREP($pirepid);       
        if(!$entry) {
            LedgerData::addPayment($params);
        } else {
            LedgerData::editPayment($entry->id, $params);
        }
        
        PilotData::resetPilotPay($pirep->pilotid);
        
        return $amount;
    }

    /**
     * Add a comment to the flight report
     * 
     * @param int $pirep_id PIREP to add a comment to
     * @param int $user_id User ID the comment is coming from
     * @param string $comment Comment to add
     * @return
     */
    public static function addComment($pirep_id, $user_id, $comment) {
        
        $comment = DB::escape($comment);
        $sql = "INSERT INTO ".TABLE_PREFIX."pirepcomments (`pirepid`, `pilotid`, `comment`, `postdate`)
					VALUES ($pirep_id, $user_id, '$comment', NOW())";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }


    /**
     * PIREPData::getAllFields()
     * 
     * @return
     */
    public static function getAllFields() {
        return DB::get_results('SELECT * FROM '.TABLE_PREFIX.'pirepfields');
    }

    /**
     * Get all of the "custom fields" for a pirep
     */
    public static function getFieldData($pirepid) {
        $sql = 'SELECT f.title, f.name, v.value
				FROM '.TABLE_PREFIX.'pirepfields f
				LEFT JOIN '.TABLE_PREFIX.'pirepvalues v
					ON f.fieldid=v.fieldid AND v.pirepid='.intval($pirepid);

        return DB::get_results($sql);
    }

    /**
     * PIREPData::getFieldInfo()
     * 
     * @param mixed $id
     * @return
     */
    public static function getFieldInfo($id) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pirepfields
					WHERE fieldid=' . $id;

        return DB::get_row($sql);
    }

    /**
     * PIREPData::getFieldValue()
     * 
     * @param mixed $fieldid
     * @param mixed $pirepid
     * @return
     */
    public static function getFieldValue($fieldid, $pirepid) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pirepvalues
					WHERE fieldid=' . $fieldid . ' AND pirepid=' . $pirepid;

        $ret = DB::get_row($sql);
        return $ret->value;
    }
    
    
    /**
     * Add a custom field to be used in a PIREP
     * 
     * @param mixed $title
     * @param string $type
     * @param string $values
     * @return
     */
    public static function addField($title, $type = '', $values = '') {
        
        $fieldname = strtoupper(str_replace(' ', '_', $title));
        //$values = DB::escape($values);

        if ($type == '') $type = 'text';

        $sql = "INSERT INTO " . TABLE_PREFIX . "pirepfields (title, name, type, options)
					VALUES ('$title', '$fieldname', '$type', '$values')";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Edit the field
     */
    public static function editField($id, $title, $type, $values = '') {
        
        $fieldname = strtoupper(str_replace(' ', '_', $title));

        $sql = "UPDATE ".TABLE_PREFIX."pirepfields
				SET title='$title', name='$fieldname', type='$type', options='$values'
				WHERE fieldid=$id";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Save PIREP fields
     */
    public static function saveFields($pirepid, $list) {
        
        if (!is_array($list) || $pirepid == '') return false;

        $allfields = self::getAllFields();

        if (!$allfields) return true;

        foreach ($allfields as $field) {
            
            // See if that value already exists
            $sql = 'SELECT id FROM '.TABLE_PREFIX.'pirepvalues
					WHERE fieldid='.$field->fieldid.
                        ' AND pirepid=' . $pirepid;
                    
            $res = DB::get_row($sql);

            $fieldname = str_replace(' ', '_', $field->name);
            $value = $list[$fieldname];

            if ($res) {
                $sql = 'UPDATE ' . TABLE_PREFIX . "pirepvalues
						SET value='$value'
						WHERE fieldid=$field->fieldid
							AND pirepid=$pirepid";
            } else {
                $sql = "INSERT INTO " . TABLE_PREFIX . "pirepvalues (fieldid, pirepid, value)
						VALUES ($field->fieldid, $pirepid, '$value')";
            }

            DB::query($sql);
        }

        return true;
    }

    /**
     * PIREPData::deleteField()
     * 
     * @param mixed $id
     * @return
     */
    public static function deleteField($id) {
        
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'pirepfields WHERE fieldid=' . $id;

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;

        //TODO: delete all of the field values!
        //$sql = 'DELETE FROM '.TABLE_PREFIX.'
    }


    /**
     * Show the graph of the past week's reports. Outputs the
     *	image unless $ret == true
     * 
     * @deprecated
     */
    public static function showReportCounts($ret = false) {
        
        // Recent PIREP #'s
        $max = 0;
        $data = array();

        # Get the past 7 days
        $time_start = strtotime('-7 days');
        $time_end = date('Ymd');

        do {
            $count = PIREPData::getReportCount($time_start);
            $data[date('m/d', $time_start)] = $count;

            $time_start += SECONDS_PER_DAY;
            $check = date('Ymd', $time_start);
        } while ($check <= $time_end);

        return $data;
    }
}
