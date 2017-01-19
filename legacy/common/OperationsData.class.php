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

class OperationsData extends CodonData {

    public static function findAirport($params, $count = '', $start = '', $order_by = '') {
        
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'airports ';

        /* Build the select "WHERE" based on the columns passed, this is a generic function */
        $sql .= DB::build_where($params);

        // Order matters
        if (strlen($order_by) > 0) {
            $sql .= ' ORDER BY ' . $order_by;
        }

        if (strlen($count) != 0) {
            $sql .= ' LIMIT ' . $count;
        }

        if (strlen($start) != 0) {
            $sql .= ' OFFSET ' . $start;
        }

        $ret = DB::get_results($sql);
        return $ret;
    }
    /**
     * Get all aircraft from database
     */

    public static function getAllAirlines($onlyenabled = false) {
        
        if ($onlyenabled == true) {
            $key = 'all_airlines_active';
            $where = 'WHERE `enabled`=1';
        } else {
            $key = 'all_airlines';
            $where = '';
        }

        $all_airlines = CodonCache::read($key);

        if ($all_airlines === false) {
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . "airlines 
					{$where}
					ORDER BY `code` ASC";

            $all_airlines = DB::get_results($sql);
            if(!$all_airlines) {
                $all_airlines = array();
            }
            
            CodonCache::write($key, $all_airlines, 'long');
        }

        return $all_airlines;
    }

    /**
     * Get all of the hubs
     */
    public static function getAllHubs() {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'airports 
				WHERE `hub`=1
				ORDER BY `icao` ASC';

        return DB::get_results($sql);
    }

    /**
     * Get all of the aircraft
     */
    public static function getAllAircraft($onlyenabled = false) {
        /*$key = 'all_aircraft';
        if($onlyenabled == true)
        {
        $key .= '_enabled';
        }
        
        $all_aircraft = CodonCache::read($key);
        if($all_aircraft === false)
        {*/
        $sql = 'SELECT a.*, r.rank
					FROM ' . TABLE_PREFIX . 'aircraft a
					LEFT JOIN ' . TABLE_PREFIX . 'ranks r ON r.rankid=a.minrank';

        if ($onlyenabled == true) {
            $sql .= ' WHERE `enabled`=1 ';
        }

        $sql .= ' ORDER BY icao ASC';

        $all_aircraft = DB::get_results($sql);
        if(!$all_aircraft) {
            $all_aircraft = array();
        }

        return $all_aircraft;
    }


    /**
     * Get the aircraft a pilot can fly, pass in their rank level
     *
     * @param int $rank_level Rank level they're at
     * @return array Array of objects of the aircraft
     *
     */
    public static function getAircraftWithinRank($rank_level) {
        $sql = 'SELECT a.*, r.rank
				FROM ' . TABLE_PREFIX . 'aircraft a
				LEFT JOIN ' . TABLE_PREFIX . 'ranks r ON r.rankid=a.minrank
				WHERE a.ranklevel > ' . $rank_level;

        return DB::get_results($sql);
    }

    /**
     * Get all of the aircraft
     */
    public static function getAllAircraftSearchList($onlyenabled = false) {
        
        $key = 'all_aircraft_search';
        if ($onlyenabled == true) {
            $key .= '_enabled';
        }

        $all_aircraft = CodonCache::read($key);
        if ($all_aircraft === false) {
            $sql = 'SELECT * 
					FROM ' . TABLE_PREFIX . 'aircraft';

            if ($onlyenabled == true) {
                $sql .= ' WHERE `enabled`=1 ';
            }

            $sql .= 'GROUP BY `name`
					 ORDER BY `icao` ASC';

            $all_aircraft = DB::get_results($sql);
            CodonCache::write($key, $all_aircraft, 'long');
        }

        return $all_aircraft;
    }

    /**
     * Get an aircraft according to registration
     */
    public static function getAircraftByReg($registration) {
        $registration = DB::escape(strtoupper($registration));

        $sql = 'SELECT * 
				FROM ' . TABLE_PREFIX . 'aircraft 
				WHERE `registration`=\'' . $registration . '\'';

        return DB::get_row($sql);
    }

    /**
     * Get an aircraft by name
     */
    public static function getAircraftByName($name) {
        $name = DB::escape(strtoupper($name));

        $sql = 'SELECT * 
				FROM ' . TABLE_PREFIX . 'aircraft 
				WHERE UPPER(`name`)=\'' . $name . '\'';

        return DB::get_row($sql);
    }

    /**
     * Check an aircraft registration, against an ID and a 
     *  registration. For instance, editing an aircraft with a 
     *  registration change. This checks to see if that reg is
     *  being already used
     */

    public static function CheckRegDupe($ac_id, $reg) {
        # Search for reg that's not on the AC supplied
        $sql = "SELECT * FROM " . TABLE_PREFIX . "aircraft
				WHERE `id` != {$ac_id}
					AND `registration`='{$reg}'";

        return DB::get_results($sql);
    }

    /**
     * Get all of the airports
     */
    public static function getAllAirports() {
        $key = 'all_airports';
        $all_airports = CodonCache::read($key);

        if ($all_airports === false) {
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'airports 
					ORDER BY `icao` ASC';

            $all_airports = DB::get_results($sql);
            if(!$all_airports) {
                $all_airports = array();
            }

            CodonCache::write($key, $all_airports, 'long');
        }

        return $all_airports;
    }

    public static function getAllAirportsJSON() {
        $key = 'all_airports_json';
        $all_airports_json = CodonCache::read($key);
        $all_airports_json = false;
        if ($all_airports_json === false) {
            $json_string = array();

            $allairports = self::getAllAirports();
            foreach ($allairports as $airport) {
                $tmp = array('label' => "{$airport->icao} ({$airport->name})", 'value' => $airport->
                    icao, 'id' => $airport->id, );

                $json_string[] = $tmp;
            }

            $all_airports_json = json_encode($json_string);
            CodonCache::write($key, $all_airports_json, 'long');
        }

        return $all_airports_json;
    }

    public static function searchAirport($airport) {
        $sql = "SELECT * FROM " . TABLE_PREFIX . "airports
				WHERE `icao` LIKE '%{$airport}%' OR
						`name` LIKE '%{$airport}%'";

        return DB::get_results($sql);
    }

    /**
     * Get information about a specific aircraft
     */
    public static function getAircraftInfo($id) {
        $id = DB::escape($id);

        return DB::get_row('SELECT * FROM ' . TABLE_PREFIX . 'aircraft 
							WHERE `id`=' . $id);
    }

    public static function getAirlineByCode($code) {
        
        $code = strtoupper($code);
        $airline = DB::get_row('SELECT * FROM ' . TABLE_PREFIX . 'airlines 
							     WHERE `code`=\'' . $code . '\'');
        return $airline;
    }

    public static function getAirlineByID($id) {
        return DB::get_row('SELECT * FROM ' . TABLE_PREFIX . 'airlines 
							WHERE `id`=\'' . $id . '\'');
    }

    /**
     * Add an airline
     */
    public static function addAirline($code, $name) {
        $code = strtoupper($code);
        $name = DB::escape($name);

        $sql = "INSERT INTO " . TABLE_PREFIX . "airlines (
					`code`, `name`) 
				VALUES ('$code', '$name')";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_airlines');
        CodonCache::delete('all_airlines_active');

        return true;
    }

    /**
     * OperationsData::editAirline()
     * 
     * @param mixed $id
     * @param mixed $code
     * @param mixed $name
     * @param bool $enabled
     * @return
     */
    public static function editAirline($id, $code, $name, $enabled = true) {
        
        $old_airline = self::getAirlineByID($id);
        
        $code = DB::escape($code);
        $name = DB::escape($name);

        if ($enabled) $enabled = 1;
        else  $enabled = 0;

        $sql = "UPDATE " . TABLE_PREFIX . "airlines 
				SET `code`='$code', `name`='$name', `enabled`=$enabled 
				WHERE id=$id";

        $res = DB::query($sql);
        if (DB::errno() != 0) return false;
        
        // Update tables to reflect new values
        $tables = array('pilots', 'pireps', 'schedules');
        foreach($tables as $t) {
            
            $sql = 'UPDATE '.TABLE_PREFIX.$t.' 
                    SET `code`=\''.$code.'\' 
                    WHERE `code`='.$old_airline->code;
        
            DB::query($sql);
        }

        CodonCache::delete('airline_' . $code);
        CodonCache::delete('all_airlines');
        CodonCache::delete('all_airlines_active');

        return true;
    }

    /**
     * Add an aircraft
     * 
     * $data = array(	'icao'=>$this->post->icao,
     * 'name'=>$this->post->name,
     * 'fullname'=>$this->post->fullname,
     * 'registration'=>$this->post->registration,
     * 'downloadlink'=>$this->post->downloadlink,
     * 'imagelink'=>$this->post->imagelink,
     * 'range'=>$this->post->range,
     * 'weight'=>$this->post->weight,
     * 'cruise'=>$this->post->cruise,
     * 'maxpax'=>$this->post->maxpax,
     * 'maxcargo'=>$this->post->maxcargo,
     * 'enabled'=>$this->post->enabled);
     */
    public static function addAircraft($data) {
        
        /*$data = array('icao'=>$this->post->icao,
        'name'=>$this->post->name,
        'fullname'=>$this->post->fullname,
        'registration'=>$this->post->registration,
        'downloadlink'=>$this->post->downloadlink,
        'imagelink'=>$this->post->imagelink,
        'range'=>$this->post->range,
        'weight'=>$this->post->weight,
        'cruise'=>$this->post->cruise,
        'maxpax'=>$this->post->maxpax,
        'maxcargo'=>$this->post->maxcargo,
        'enabled'=>$this->post->enabled);*/

        $data['icao'] = DB::escape(strtoupper($data['icao']));
        $data['name'] = DB::escape(strtoupper($data['name']));
        $data['registration'] = DB::escape(strtoupper($data['registration']));

        $data['range'] = ($data['range'] == '') ? 0 : $data['range'];
        $data['weight'] = ($data['weight'] == '') ? 0 : $data['weight'];
        $data['cruise'] = ($data['cruise'] == '') ? 0 : $data['cruise'];

        $data['range'] = str_replace(',', '', $data['range']);
        $data['weight'] = str_replace(',', '', $data['weight']);
        $data['cruise'] = str_replace(',', '', $data['cruise']);
        $data['maxpax'] = str_replace(',', '', $data['maxpax']);
        $data['maxcargo'] = str_replace(',', '', $data['maxcargo']);

        if ($data['enabled'] === true || $data['enabled'] == '1') $data['enabled'] = 1;
        else  $data['enabled'] = 0;

        if ($data['minrank'] > 0) {
            $data['ranklevel'] = RanksData::getRankLevel($data['minrank']);
        } else {
            $data['ranklevel'] = '0';
        }

        $cols = array();
        $col_values = array();

        foreach ($data as $key => $value) {
            $cols[] = "`{$key}`";

            $value = DB::escape($value);
            $col_values[] = "'{$value}'";
        }

        $cols = implode(', ', $cols);
        $col_values = implode(', ', $col_values);

        $sql = "INSERT INTO " . TABLE_PREFIX . "aircraft 
				({$cols}) VALUES ({$col_values})";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_aircraft');
        CodonCache::delete('all_aircraft_enabled');
        CodonCache::delete('all_aircraft_search');
        CodonCache::delete('all_aircraft_search_enabled');

        return true;
    }

    /**
     * Edit an aircraft
     */
    public static function editAircraft($data) {
        
        $data['icao'] = DB::escape(strtoupper($data['icao']));
        $data['name'] = DB::escape(strtoupper($data['name']));
        $data['registration'] = DB::escape(strtoupper($data['registration']));

        $data['range'] = ($data['range'] == '') ? 0 : $data['range'];
        $data['weight'] = ($data['weight'] == '') ? 0 : $data['weight'];
        $data['cruise'] = ($data['cruise'] == '') ? 0 : $data['cruise'];

        $data['range'] = str_replace(',', '', $data['range']);
        $data['weight'] = str_replace(',', '', $data['weight']);
        $data['cruise'] = str_replace(',', '', $data['cruise']);
        $data['maxpax'] = str_replace(',', '', $data['maxpax']);
        $data['maxcargo'] = str_replace(',', '', $data['maxcargo']);

        if (empty($data['minrank'])) {
            $data['minrank'] = 0;
        }

        if ($data['enabled'] === true || $data['enabled'] == '1') $data['enabled'] = 1;
        else  $data['enabled'] = 0;

        if ($data['minrank'] > 0) {
            $data['ranklevel'] = RanksData::getRankLevel($data['minrank']);
        } else {
            $data['ranklevel'] = 0;
        }

        $sql = "UPDATE " . TABLE_PREFIX . "aircraft SET ";
        $sql .= DB::build_update($data);
        $sql .= " WHERE `id`={$data['id']}";

        /*$sql = "UPDATE " . TABLE_PREFIX."aircraft
        SET `icao`='{$data['icao']}', `name`='{$data['name']}', `fullname`='{$data['fullname']}',
        `registration`='{$data['registration']}', `downloadlink`='{$data['downloadlink']}', 
        `imagelink`='{$data['imagelink']}', `range`='{$data['range']}', `weight`='{$data['weight']}',
        `cruise`='{$data['cruise']}', `maxpax`='{$data['maxpax']}', `maxcargo`='{$data['maxcargo']}',
        `minrank`={$data['minrank']}, `ranklevel`={$rank_level}, `enabled`={$data['enabled']}
        WHERE `id`={$data['id']}";*/

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_aircraft');
        CodonCache::delete('all_aircraft_enabled');
        CodonCache::delete('all_aircraft_search');
        CodonCache::delete('all_aircraft_search_enabled');

        return true;
    }

    /**
     * This is method deleteAircraft
     *
     * @param mixed $aircraft_id This is a description
     * @return mixed This is the return value description
     *
     */
    public static function deleteAircraft($aircraft_id) {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'aircraft 
				WHERE `id`=' . $aircraft_id;

        DB::query($sql);

        CodonCache::delete('all_aircraft');
        CodonCache::delete('all_aircraft_enabled');
        CodonCache::delete('all_aircraft_search');
        CodonCache::delete('all_aircraft_search_enabled');

        return true;
    }

    public static function updateAircraftRankLevels() {
        $all_aircraft = self::getAllAircraft(false);
        if (!$all_aircraft) {
            return;
        }

        foreach ($all_aircraft as $aircraft) {
            $rank_level = RanksData::getRankLevel($aircraft->minrank);

            $sql = "UPDATE " . TABLE_PREFIX . "aircraft
					SET `ranklevel`={$rank_level}
					WHERE `id`={$aircraft->id}";

            DB::query($sql);
        }
    }

    /**
     * Add an airport
     * 
     * $data = array(
     * 'icao' => 'KJFK',
     * 'name' => 'Kennedy International',
     * 'country' => 'USA',
     * 'lat' => '40.6398',
     * 'lng' => '-73.7787',
     * 'hub' => 0,
     * 'fuelprice' => 0
     * );
     * 
     */
    public static function addAirport($data) {

        /*$data = array(
        'icao' => 'KJFK',
        'name' => 'Kennedy International',
        'country' => 'USA',
        'lat' => '40.6398',
        'lng' => '-73.7787',
        'hub' => false,
        'fuelprice' => 0
        );
        */

        if ($data['icao'] == '') return false;

        $data['icao'] = strtoupper(DB::escape($data['icao']));
        $data['name'] = DB::escape($data['name']);

        if ($data['hub'] === true) $data['hub'] = 1;
        else  $data['hub'] = 0;

        if ($data['fuelprice'] == '') $data['fuelprice'] = 0;

        if (!isset($data['chartlink'])) {
            $data['chartlink'] = '';
        }

        $sql = "INSERT INTO " . TABLE_PREFIX . "airports 
					(	`icao`, `name`, `country`, `lat`, `lng`, `hub`, `chartlink`, `fuelprice`)
					VALUES (
						'{$data['icao']}', '{$data['name']}', '{$data['country']}', 
						{$data['lat']}, {$data['lng']}, {$data['hub']}, '{$data['chartlink']}', {$data['fuelprice']})";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_airports');
        CodonCache::delete('all_airports_json');
        CodonCache::delete('get_airport_' . $data['icao']);
        return true;
    }

    /**
     * Edit the airport
     * $data = array(
     * 'icao' => 'KJFK',
     * 'name' => 'Kennedy International',
     * 'country' => 'USA',
     * 'lat' => '40.6398',
     * 'lng' => '-73.7787',
     * 'hub' => false,
     * 'fuelprice' => 0
     * );
     */
    public static function editAirport($data) {
        $data['icao'] = strtoupper(DB::escape($data['icao']));
        $data['name'] = DB::escape($data['name']);

        if ($data['hub'] === true) $data['hub'] = 1;
        else  $data['hub'] = 0;

        if ($data['fuelprice'] == '') $data['fuelprice'] = 0;

        $sql = "UPDATE " . TABLE_PREFIX . "airports
					SET `icao`='{$data['icao']}', `name`='{$data['name']}', `country`='{$data['country']}', 
						`lat`={$data['lat']}, `lng`={$data['lng']}, `hub`={$data['hub']}, 
						`chartlink`='{$data['chartlink']}', `fuelprice`={$data['fuelprice']}
					WHERE `icao`='{$data['icao']}'";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('get_airport_' . $data['icao']);
        CodonCache::delete('all_airports_json');
        CodonCache::delete('all_airports');
        return true;
    }

    public static function removeAirport($icao) {
        $icao = DB::escape($icao);
        $icao = strtoupper($icao);
        $sql = "DELETE FROM " . TABLE_PREFIX . "airports WHERE `icao`='{$icao}'";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('get_airport_' . $icao);
        CodonCache::delete('all_airports_json');
        CodonCache::delete('all_airports');
        return true;
    }
    
    public static function deleteAllAirports() {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'airports';
        $res = DB::query($sql);
        if (DB::errno() != 0) return false;
        return true;
    }
    
    /**
     * Get information about an airport
     */
    public static function getAirportInfo($icao) {
        $icao = strtoupper(DB::escape($icao));

        /*$key = 'get_airport_'.$icao;
        
        $airport_info = CodonCache::read($key);
        if($airport_info === false)
        {*/
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'airports 
				WHERE `icao`=\'' . $icao . '\'';

        $airport_info = DB::get_row($sql);
        /*	CodonCache::write($key, $airport_info, 'long');
        }*/

        return $airport_info;
    }


    /**
     * Get the distance between two airports
     *
     * @param mixed $depicao ICAO or object of the departure airport
     * @param mixed $arricao ICAO or object of the destination airport
     * @return int The distance
     *
     */
    public static function getAirportDistance($depicao, $arricao) {
        if (!is_object($depicao)) $depicao = self::getAirportInfo($depicao);

        if (!is_object($arricao)) $arricao = self::getAirportInfo($arricao);

        return SchedulesData::distanceBetweenPoints($depicao->lat, $depicao->lng, $arricao->
            lat, $arricao->lng);
    }

    /**
     * Retrieve Airport Information
     */

    public static function RetrieveAirportInfo($icao) {
        $icao = strtoupper($icao);

        if (Config::Get('AIRPORT_LOOKUP_SERVER') == 'geonames') {
            $url = Config::Get('GEONAME_API_SERVER') .
                '/searchJSON?maxRows=1&style=medium&featureCode=AIRP&type=json&q=' . $icao;
        } elseif (Config::Get('AIRPORT_LOOKUP_SERVER') == 'phpvms') {
            $url = Config::Get('PHPVMS_API_SERVER') . '/index.php/airport/get/' . $icao;
        }

        # Updated to use CodonWebServer instead of simplexml_load_url() straight
        #	Could cause errors
        $file = new CodonWebService();
        $contents = @$file->get($url);

        $reader = json_decode($contents);
        if ($reader->totalResultsCount == 0 || !$reader) {
            return false;
        } else {
            if (isset($reader->geonames)) {
                $apt = $reader->geonames[0];
            } elseif (isset($reader->airports)) {
                $apt = $reader->airports[0];
            }

            if (!isset($apt->jeta)) {
                $apt->jeta = '';
            }

            // Add the AP
            $data = array('icao' => $icao, 'name' => $apt->name, 'country' => $apt->
                countryName, 'lat' => $apt->lat, 'lng' => $apt->lng, 'hub' => false, 'fuelprice' =>
                $apt->jeta);

            OperationsData::addAirport($data);
        }

        return self::GetAirportInfo($icao);
    }
}
