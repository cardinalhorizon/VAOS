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

class CentralData extends CodonData {
    
    public static $xml;
    public static $json;
    
    public static $xml_data = '';
    public static $xml_response = '';
    
    public static $debug = false;
    public static $response;
    public static $last_error;
    public static $method;
    
    public static $type = 'xml';

    /*	DO NOT try to circumvent these limits.
    They're also tracked server-side. If you change them,
    you will be penalized or banned. 
    
    These DO NOT AFFECT YOUR SCORE */
    public static $limits = array(
        'update_vainfo' => 6, 
        'update_schedules' => 12,
        'process_airport_list' => 1, 
        'update_pilots' => 24, 
        'update_pireps' => 8
    );

    /**
     * CentralData::central_enabled()
     * 
     * @return
     */
    private static function central_enabled() {
        
        /* Cover both, since format changed */
        if (Config::Get('VACENTRAL_ENABLED') && Config::Get('VACENTRAL_API_KEY') != '') {
            return true;
        }

        return false;
    }

    /**
     * CentralData::sendToCentral()
     * 
     * @return
     */
    private static function sendToCentral() {
        
        // Cover old and new format
        $api_server = Config::Get('VACENTRAL_API_SERVER');
        if ($api_server == '') {
            $api_server = Config::Get('PHPVMS_API_SERVER');
        }

        ob_start();
        $web_service = new CodonWebService();
        $web_service->setOptions(array(CURLOPT_USERAGENT => 'phpVMS ('.PHPVMS_VERSION.')'));
        
        if(self::$type == 'xml') {
            $data = self::$xml->asXML();
        } else {
            $data = json_encode(self::$json);
        }
        
        self::$xml_response = $web_service->post($api_server . '/update', $data);

        if (!self::$xml_response) {
            if (Config::Get('VACENTRAL_DEBUG_MODE') == true) {
                Debug::log(self::$method . ' - ' . date('h:i:s A - m/d/Y'), 'vacentral');
                Debug::log(print_r($web_service->errors, true), 'vacentral');
            }

            self::$last_error = 'No response from API server';
            return false;
        }

        if(self::$type == 'xml') {
            self::$response = @simplexml_load_string(self::$xml_response);    
        } else {
            self::$response = json_decode(self::$xml_response);
        }
        
        ob_end_clean();

        if (!is_object(self::$response)) {
            if (Config::Get('VACENTRAL_DEBUG_MODE') == true) {
                Debug::log(self::$method . ' - ' . date('h:i:s A - m/d/Y'), 'vacentral');
                Debug::log('   - no response from server', 'vacentral');
            }

            self::$last_error = 'No response from API server';
            return false;
        }

        if (Config::Get('VACENTRAL_DEBUG_MODE') == true) {
            Debug::log(self::$method . ' - ' . date('h:i:s A - m/d/Y'), 'vacentral');
            Debug::log('   - ' . (string )self::$response->detail, 'vacentral');
            Debug::log('   - ' . (string )self::$response->dbg, 'vacentral');

            # Extra detail
            if (Config::Get('VACENTRAL_DEBUG_DETAIL') == '2') {
                Debug::log('SENT XML: ', 'vacentral');
                Debug::log($data, 'vacentral');

                Debug::log('RECIEVED XML: ', 'vacentral');
                Debug::log(self::$response, 'vacentral');

                Debug::log('', 'vacentral');
            }
        }

        if ((int)self::$response->responsecode != 200) {
            if (Config::Get('VACENTRAL_DEBUG_MODE') == true) {
                Debug::log(self::$method . ' - ', 'vacentral');
                Debug::log('   - ' . (string )self::$response->message->detail, 'vacentral');
            }

            self::$last_error = self::$response->message->detail;
            return false;
        }

        return true;
    }
    
    /**
     * CentralData::startBody()
     * 
     * @param mixed $method
     * @return void
     */
    protected static function startBody($method) {

        $api_key = Config::Get('VACENTRAL_API_KEY');
        
        # Determine the type
        self::$type = strtolower(trim(Config::Get('VACENTRAL_DATA_FORMAT')));
        if(self::$type !== 'xml' && self::$type !== 'json') {
            self::$type = 'xml';
        }
        
        
        if(self::$type == 'xml') {
            self::$xml = new SimpleXMLElement('<vacentral/>');
        } elseif(self::$type == 'json') {
            self::$json = array();
        }
        
        self::$method = $method;
        self::addElement(null, 'siteurl', SITE_URL);
        self::addElement(null, 'apikey', $api_key);
        self::addElement(null, 'version', PHPVMS_VERSION);
        self::addElement(null, 'method', $method);
        
        if (Config::Get('VACENTRAL_DEBUG_MODE') == true) {
            self::addElement(null, 'debug', true);
        }
    }
    
    /**
     * CentralData::addElement()
     * 
     * @return void
     */
    protected static function addElement($parent = null, $name, $value = '', $children = array()) {
        
        if(self::$type === 'xml') {
            
            if($parent === null) {
                $child = self::$xml->addChild($name, $value);
            } else {
                $child = $parent->addChild($name, $value);
            }
            
            # Add any children who might exist...            
            if(count($children) > 0) {
                foreach($children as $key => $value) {
                    $child->addChild($key, $value);
                }
            }
            
            return $child; 
                                    
        } elseif(self::$type === 'json') {
            
            if($parent === null) {
                if(is_array($children) && count($children) > 0) {
                    
                    if(!is_array(self::$json[$name])) {
                        self::$json[$name] = array();
                    }
                    
                    self::$json[$name][] = $children;
                } else {
                    self::$json[$name] = $value;
                }
                
            } else {
                if(is_array($children) && count($children) > 0) {
                    
                    if(!is_array(self::$json[$parent][$name])) {
                        self::$json[$parent][$name] = array();
                    }
                    
                    self::$json[$parent][$name][] = $children;
                } else {
                    self::$json[$parent][$name] = $value;
                }
            }
            
            return $name;
        }
    
    }    
    
    /**
     * CentralData::send_vastats()
     * 
     * @return
     */
    public static function send_vastats() {
        
        if (!self::central_enabled()) return false;

        if (self::$debug === false) {
            $within_timelimit = CronData::check_hoursdiff('update_vainfo', self::$limits['update_vainfo']);
            if ($within_timelimit == true) {
                return false;
            }
        }

        self::startBody('update_vainfo');
        self::addElement(null, 'pilotcount', StatsData::PilotCount());
        self::addElement(null, 'totalhours', StatsData::TotalHours());
        self::addElement(null, 'totalflights', StatsData::TotalFlights());
        self::addElement(null, 'totalschedules', StatsData::TotalSchedules());
        
        $all_news = SiteData::getAllNews();
        if(count($all_news) > 0) {
            
            $news_parent = self::addElement(null, 'newsitems');
            foreach($all_news as $news) {
                
                $body = str_ireplace('<br>', "\n", $news->body);
                $body = str_ireplace('<br />', "\n", $body);
                $body = htmlentities(strip_tags($body));
                
                $news_xml = self::addElement($news_parent, 'news', null, array(
                        'id' => $news->id,
                        'subject' => $news->subject,
                        'body' => $body,
                        'postdate' => $news->postdate,
                        'postedby' => $news->postedby,
                    )
                );
            }
        }
        
        # Some of the settings
        self::addElement(null, 'livefuel', Config::Get('FUEL_GET_LIVE_PRICE'));

        # Package and send
        CronData::set_lastupdate('update_vainfo');
        return self::sendToCentral();
    }
    
    /**
     * CentralData::send_news()
     * 
     * @return
     */
    public static function send_news() {
        
        if (!self::central_enabled()) return false;
        
        $all_news = SiteData::getAllNews();
        if(!is_array($all_news) && count($all_news) == 0) {
            return false;
        }
        
        self::startBody('vanews');
        self::addElement(null, 'total', count($all));
        $news_parent = self::addElement('newsitems');
        foreach($all_news as $news) {
            $news_xml = self::addElement($news_parent, 'news', null, array(
                'id' => $news->id,
                'subject' => $news->subject,
                'body' => '<![CDATA['.$news->body.']]>', 
                'postdate' => $news->postdate,
                'postedby' => $news->postedby,
            ));

        }
        
        CronData::set_lastupdate('vanews');
        $res = self::sendToCentral();

        return $res;
    }

    /**
     * CentralData::send_schedules()
     * 
     * @return
     */
    public static function send_schedules() {
        
        if (!self::central_enabled()) return false;

        if (self::$debug === false) {
            $within_timelimit = CronData::check_hoursdiff('update_schedules', self::$limits['update_schedules']);
            if ($within_timelimit == true) {
                return false;
            }
        }

        $schedules = SchedulesData::findSchedules(array('s.enabled' => '1'));
        if (!is_array($schedules)) {
            return false;
        }

        self::startBody('update_schedules');
        self::addElement(null, 'total', count($schedules));
        $schedules_parent = self::addElement(null, 'schedules');
        foreach ($schedules as $sched) {
            
            $sp = self::addElement($schedules_parent, 'schedule', null, array(
                'flightnum' => $sched->code . $sched->flightnum,
                'depicao' => $sched->depicao,
                'arricao' => $sched->arricao,
                'aircraft' => $sched->aircraft,
                'registration' => $sched->registration,
                'distance' => $sched->distance,
                'daysofweek' => $sched->daysofweek,
                'price' => $sched->price,
                'flighttype' => $sched->flighttype,
                'notes' => $sched->notes,
                'deptime' => $sched->deptime,
                'arrtime' => $sched->arrtime,
            ));
        }

        # Package and send
        CronData::set_lastupdate('update_schedules');
        $res = self::sendToCentral();

        return $res;
    }

    /**
     * CentralData::process_airport_list()
     * 
     * @return
     */
    /*protected static function process_airport_list() {
        
        self::startBody('process_airport_list');

        foreach (self::$response->airport as $apt) {
            // Get from API
            $apt = OperationsData::GetAirportInfo($apt->icao);
            if ($apt) {
                $airport = self::addElement(null, 'airport', null, array(
                    'icao' => $apt->icao,
                    'name' => $apt->name,
                    'location' => $apt->country,
                    'lat' => $apt->lat,
                    'lng' => $apt->lng,
                ));
            }
        }
    }*/

    /**
     * CentralData::send_pilots()
     * 
     * @return
     */
    public static function send_pilots() {
        
        if (!self::central_enabled()) return false;

        if (self::$debug === false) {
            $within_timelimit = CronData::check_hoursdiff('update_pilots', self::$limits['update_pilots']);
            if ($within_timelimit == true) {
                return false;
            }
        }
        
        if(!($allpilots = PilotData::getAllPilots())) {
            return false;
        }        
        
        self::startBody('update_pilots');
        self::addElement(null, 'total', count($allpilots));
        foreach ($allpilots as $pilot) {
            $pc = self::addElement(null, 'pilot', null, array(
                'pilotid' => PilotData::GetPilotCode($pilot->code, $pilot->pilotid),
                'pilotname' => $pilot->firstname.' '.$pilot->lastname,
                'location' => $pilot->location,
            ));
        }

        CronData::set_lastupdate('update_pilots');
        return self::sendToCentral();
    }

    /**
     * CentralData::send_all_pireps()
     * 
     * @return
     */
    public static function send_all_pireps() {
        
        if (!self::central_enabled()) return false;

        if (self::$debug === false) {
            $within_timelimit = CronData::check_hoursdiff('update_pireps', self::$limits['update_pireps']);
            if ($within_timelimit == true) {
                return false;
            }
        }

        $allpireps = PIREPData::findPIREPS(array(
            //'DATE_SUB(CURDATE(), INTERVAL 3 MONTH) <= p.submitdate'
        ));

        if (!$allpireps) {
            return false;
        }
        

        // Set them all to have not been exported
        PIREPData::setAllExportStatus(false);

        self::startBody('update_pireps');
        self::addElement(null, 'total', count($allpireps));

        foreach ($allpireps as $pirep) {
            # Skip erronious entries
            if ($pirep->aircraft == '') continue;

            self::get_pirep_xml($pirep);
        }

        CronData::set_lastupdate('update_pireps');
        $resp = self::sendToCentral();

        // Only if we get a valid response, set the PIREPs to exported
        if ($resp === true) {
            PIREPData::setAllExportStatus(true);
            return true;
        }
    }

    /**
     * CentralData::send_pirep()
     * 
     * @param mixed $pirep_id
     * @return
     */
    public static function send_pirep($pirep_id) {
        
        if (!self::central_enabled()) return false;

        if ($pirep_id == '') {
            return;
        }

        if (!($pirep = PIREPData::getReportDetails($pirep_id))) {
            return false;
        }
        
        PIREPData::setExportedStatus($pirep_id, false);

        self::startBody('add_pirep');
        self::get_pirep_xml($pirep);

        CronData::set_lastupdate('add_pirep');
        $resp = self::sendToCentral();

        if ($resp === true) {
            PIREPData::setExportedStatus($pirep_id, true);
            return true;
        }
    }

    /**
     * CentralData::get_pirep_xml()
     * 
     * @param mixed $pirep
     * @return
     */
    protected static function get_pirep_xml($pirep) {
        
        $pilotid = PilotData::getPilotCode($pirep->code, $pirep->pilotid);

        $pirep_xml = self::addElement(null, 'pirep', null, array(
            'uniqueid' => $pirep->pirepid,
            'pilotid' => $pilotid,
            'pilotname' => $pirep->firstname . ' ' . $pirep->lastname,
            'flightnum' => $pirep->code . $pirep->flightnum,
            'depicao' => $pirep->depicao,
            'arricao' => $pirep->arricao,
            'aircraft' => $pirep->aircraft,
            'registration' => $pirep->registration,
            'flighttime' => $pirep->flighttime_stamp,
            'submitdate' => $pirep->submitdate,
            'modifieddate' => $pirep->modifieddate,
            'flighttype' => $pirep->flighttype,
            'load' => $pirep->load,
            'fuelused' => $pirep->fuelused,
            'fuelprice' => $pirep->fuelprice, 
            'pilotpay' => $pirep->pilotpay,
            'price' => $pirep->price,
            'source' => $pirep->source,
            'revenue' => $pirep->revenue,
        ));
    }

    /**
     * CentralData::send_all_acars()
     * 
     * @return
     */
    public static function send_all_acars() {
        
        if (!self::central_enabled()) return false;

        if (!($acars_flights = ACARSData::getAllFlights())) {
            return false;
        }

        self::startBody('update_acars');

        foreach ($acars_flights as $flight) {
            self::create_acars_flight($flight);
        }

        CronData::set_lastupdate('update_acars');
        return self::sendToCentral();
    }

    /**
     * CentralData::send_acars_data()
     * 
     * @param mixed $flight
     * @return
     */
    public static function send_acars_data($flight) {
        
        if (!self::central_enabled()) return false;

        self::startBody('update_acars_flight');
        self::create_acars_flight($flight);

        CronData::set_lastupdate('update_acars');
        return self::sendToCentral();
    }

    /**
     * CentralData::create_acars_flight()
     * 
     * @param mixed $flight
     * @return
     */
    protected static function create_acars_flight($flight) {
        
        if (is_object($flight)) {
            $flight = (array) $flight;
        }

        // If a unique was specified
        if (isset($flight['unique_id'])) {
            $flight['id'] = $flight['unique_id'];
        }

        $acars_xml = self::addElement(null, 'flight', null, array(
            'unique_id' => $flight['id'],
            'pilotid' => PilotData::GetPilotCode($flight['code'], $flight['pilotid']),
            'pilotname' => $flight['pilotname'],
            'flightnum' => $flight['flightnum'],
            'aircraft' => $flight['aircraft'],
            'lat' => $flight['lat'],
            'lng' => $flight['lng'],
            'depicao' => $flight['depicao'],
            'arricao' => $flight['arricao'],
            'deptime' => $flight['deptime'],
            'arrtime' => $flight['arrtime'],
            'heading' => $flight['heading'],
            'phase' => $flight['phasedetail'],
            'alt' => $flight['alt'],
            'gs' => $flight['gs'],
            'distremain' => $flight['distremain'],
            'timeremain' => $flight['timeremaining'],
            'client' => $flight['client'],
            'lastupdate' => $flight['lastupdate']
        ));
        
    }
}
