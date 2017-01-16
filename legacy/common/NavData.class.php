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

class NavData extends CodonData {

    public static $nat_pattern = '/^([0-9]+)([A-Za-z]+)/';

    /**
     * Pass in a string with the route, and return back an array
     * with the data about each segment of the route. Pass a schedule
     * result into it.
     * 
     * You can pass in a PIREP, schedule, or ACARS result, as long as it
     * has the following fields:
     *	lat
     *	lng
     *	route
     * 
     * To cache the route, use ScheduleData::getRouteDetails() instead.
     * This function bypasses any cached info
     *
     * @param mixed $route_string This is a description
     * @return mixed This is the return value description
     *
     */
    public static function parseRoute($schedule) {
        $fromlat = $schedule->deplat;
        $fromlng = $schedule->deplng;
        $route_string = $schedule->route;

        if ($route_string == '') {
            return array();
        }

        // Remove any SID/STAR text
        $route_string = str_replace('SID', '', $route_string);
        $route_string = str_replace('STAR', '', $route_string);
        $route_string = str_replace('DCT', '', $route_string);

        $navpoints = array();
        $all_points = explode(' ', $route_string);

        foreach ($all_points as $key => $value) {
            if (empty($value) === true) {
                continue;
            }

            $navpoints[] = strtoupper(trim($value));
        }

        $allpoints = array();
        $total = count($navpoints);
        $airways = self::getAirways($navpoints);

        for ($i = 0; $i < $total; $i++) {
            $name = self::cleanName($navpoints[$i]);
            /*	the current point is an airway, so go through
            the airway list and add each corresponding point
            between the entry and exit to the list. */
            if (isset($airways[$name])) {
                $entry_name = self::cleanName($navpoints[$i - 1]);
                $exit_name = self::cleanName($navpoints[$i + 1]);

                $entry = self::getPointIndex($entry_name, $airways[$name]);
                $exit = self::getPointIndex($exit_name, $airways[$name]);

                if ($entry == -1) {
                    $entry = $exit;
                } else {
                    /*	Add information abotu the entry point in first,
                    if it's valid and exists */
                    $allpoints[$entry_name] = $airways[$name][$entry];
                }

                if ($exit == -1) {
                    continue;
                }

                if ($entry < $exit) {
                    # Go forwards through the list adding each one
                    for ($l = $entry; $l <= $exit; $l++) {
                        $allpoints[$airways[$name][$l]->name] = $airways[$name][$l];
                    }
                } elseif ($entry > $exit) {
                    # Go backwards through the list
                    for ($l = $exit; $l >= $entry; $l--) {
                        $point_name = self::cleanName($airways[$name][$l]->name);
                        $allpoints[$point_name] = $airways[$name][$l];
                    }
                } elseif ($entry == $exit) {
                    $point_name = self::cleanName($airways[$name][$l]->name);
                    $allpoints[$point_name] = $airways[$name][$entry];
                }

                # Now add the exit point, and increment the main counter by one
                if ($exit > -1) {
                    $allpoints[$exit_name] = $airways[$name][$exit];
                }

                continue;
            } else {
                /* This nav point already exists in the list, don't add it
                again */
                if (isset($allpoints[$navpoints[$i]])) {
                    continue;
                }

                /*	Means it is a track, so go into processing it
                See if it's something like XXXX/YYYY
                */
                if (substr_count($navpoints[$i], '/') > 0) {
                    $name = $navpoints[$i];
                    $point_name = explode('/', $name);

                    preg_match(self::$nat_pattern, $point_name[0], $matches);

                    $coord = $matches[1];
                    $lat = $matches[2] . $coord[0] . $coord[1] . '.' . $coord[2] . $coord[3];

                    /*	Match the second set of coordinates */

                    # Read the second set
                    preg_match(self::$nat_pattern, $point_name[1], $matches);
                    if ($matches == 0) {
                        continue;
                    }

                    $coord = $matches[1];
                    $lng = $matches[2] . $coord[0] . $coord[1] . $coord[2] . '.' . $coord[3];

                    /*	Now convert into decimal coordinates */
                    $coords = $lat . ' ' . $lng;
                    $coords = Util::get_coordinates($coords);

                    if (empty($coords['lat']) || empty($coords['lng'])) {
                        unset($allpoints[$navpoints[$i]]);
                        continue;
                    }

                    $tmp = new stdClass();
                    $tmp->id = 0;
                    $tmp->type = NAV_TRACK;
                    $tmp->name = $name;
                    $tmp->title = $name;
                    $tmp->lat = $coords['lat'];
                    $tmp->lng = $coords['lng'];
                    $tmp->airway = '';
                    $tmp->sequence = 0;
                    $tmp->freq = '';

                    $allpoints[$navpoints[$i]] = $tmp;
                    unset($point_name);
                    unset($matches);
                    unset($tmp);
                } else {
                    $allpoints[$navpoints[$i]] = $navpoints[$i];
                    $navpoint_list[] = $navpoints[$i];
                }
            }
        }

        $navpoint_list_details = self::getNavDetails($navpoint_list);

        foreach ($navpoint_list_details as $point => $list) {
            $allpoints[$point] = $list;
        }

        unset($navpoint_list_details);

        /*	How will this work - loop through each point, and
        decide which one we'll use, determined by the
        one which is the shortest distance from the previous 
        
        Go in the order of the ones passed in.
        */

        foreach ($allpoints as $point_name => $point_details) {
            if (is_string($point_details)) {
                unset($allpoints[$point_name]);
                continue;
            }

            if (!is_array($point_details)) {
                continue;
            }

            $results_count = count($point_details);

            if ($results_count == 1) {
                $allpoints[$point_name] = $point_details[0];
            } elseif ($results_count > 1) {
                /* There is more than one, so find the one with the shortest
                distance from the previous point out of all the ones */

                $index = 0;
                $dist = 0;

                /* Set the inital settings */
                $lowest_index = 0;
                $lowest = $point_details[$lowest_index];
                $lowest_dist = SchedulesData::distanceBetweenPoints($fromlat, $fromlng, $lowest->
                    lat, $lowest->lng);

                foreach ($point_details as $p) {
                    $dist = SchedulesData::distanceBetweenPoints($fromlat, $fromlng, $p->lat, $p->
                        lng);

                    if ($dist < $lowest_dist) {
                        $lowest_index = $index;
                        $lowest_dist = $dist;
                    }

                    $index++;
                }

                $allpoints[$point_name] = $point_details[$lowest_index];
            }

            $fromlat = $allpoints[$point_name]->lat;
            $fromlng = $allpoints[$point_name]->lng;
        }

        return $allpoints;
    }

    protected static function cleanName($name) {
        if (substr_count($name, '/') > 0) {
            $tmp = explode('/', $name);
            $name = $tmp[0];
            unset($tmp);
        }

        return $name;
    }

    protected static function getAirways($list) {
        foreach ($list as $key => $value) {
            $list[$key] = "'{$value}'";
        }

        $in_clause = implode(',', $list);

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . "navdata
				WHERE `airway` IN ({$in_clause})";

        $list = DB::get_results($sql);

        $return = array();

        if (!$list) return $return;

        foreach ($list as $value) {
            $return[$value->airway][] = $value;
        }

        return $return;
    }


    protected static function getPointIndex($point_name, $list) {
        $total = count($list);

        for ($i = 0; $i < $total; $i++) {
            if ($list[$i]->name == $point_name) {
                return $i;
            }
        }

        return - 1;
    }

    protected static function getNavDetails($navpoints) {
        /*	Form an IN clause so we can easily grab all the points
        which we have cached locally in the navdata table
        
        Check if an array was passed, or a string of points */
        if (is_array($navpoints) && count($navpoints) > 0) {
            $in_clause = array();
            foreach ($navpoints as $point) {
                // There's already data about it
                if (is_array($point) || is_object($point)) {
                    continue;
                }

                $in_clause[] = "'{$point}'";
            }

            $in_clause = implode(', ', $in_clause);
        } else {
            # Add commas in between, since it's space separated
            $navpoints = str_replace(' ', ', ', $navpoints);
            $in_clause = "'{$navpoints}'";
        }

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . "navdata
				WHERE `name` IN ({$in_clause}) 
				GROUP BY `lat`";

        $results = DB::get_results($sql);

        if (!$results) {
            return array();
        }

        $return_results = array();
        foreach ($results as $key => $point) {
            if (empty($point->title)) {
                $point->title = $point->name;
            }

            $return_results[$point->name][] = $point;
        }

        return $return_results;

        /* Means nothing was returned locally */
        /*if(!$results)
        {
        return self::navDetailsFromServer($navpoints);
        }
        else
        {*/
        /*	Form an array of what to return from the server,
        see what we did and didn't return */
        $notfound = array();
        $point_array = array();
        foreach ($results as $row) {
            /*	Find all instances of the navpoint in what was
            returned, and then remove it. In the end, only the
            ones which haven't been returned  are left in the 
            array */
            $keys = array_keys($navpoints, $row->name);
            foreach ($keys as $k) {
                unset($navpoints[$k]);
            }

            if ($row->lat == 0 || $row->lng == 0) {
                continue;
            }

            $point_array[$row->name][] = $row;
        }

        /* These are the navpoints left over which we didn't
        find, so try to get their information from above */
        if (count($navpoints) > 0) {
            $temp = self::navDetailsFromServer($navpoints);
            $point_array = array_merge($point_array, $temp);
            unset($temp);
        }
        //}

        return $point_array;
    }

    protected static function navDetailsFromServer($navpoints) {
        if (!is_array($navpoints) && count($navpoints) == 0) {
            return array();
        }

        /*	Send a simple XML string over:
        
        <phpvms>
        <navpoints>
        <navpoint>NAME</navpoint>
        <navpoint>NAME2</navpoint>
        </navpoints>
        </phpvms>
        
        
        @TODO: Convert send format to json, much smaller
        */
        $xml = new SimpleXMLElement('<phpvms/>');
        $nav_xml = $xml->addChild('navpoints');

        foreach ($navpoints as $p) {
            $nav_xml->addChild('navpoint', $p);
        }

        /*	Send the request, data is returned as JSON format */
        $web_service = new CodonWebService();
        $xml_response = $web_service->post(Config::Get('PHPVMS_API_SERVER') .
            '/navdata/get/json', $xml->asXML());

        $insert = array();
        $sql = 'INSERT INTO ' . TABLE_PREFIX . "navdata
				(`type`, `name`, `title`, `freq`, `lat`, `lng`) VALUES ";

        if (empty($xml_response)) {
            /*	None of those exist on the server, but cache them on this
            side so we don't keep checking over and over */
            foreach ($navpoints as $point) {
                $insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
            }

            $sql .= implode(',', $insert);
            DB::query($sql);

            return array();
        }

        $returned_points = json_decode($xml_response);

        if (empty($returned_points)) {
            foreach ($navpoints as $point) {
                $insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
            }

            $sql .= implode(',', $insert);
            DB::query($sql);

            return array();
        }

        $return = array();
        foreach ($returned_points as $point) {
            $keys = array_keys($navpoints, $point->name);
            foreach ($keys as $k) {
                unset($navpoints[$k]);
            }

            $return[$point->name][] = $point;
            $insert[] = "({$point->type}, '{$point->name}', '{$point->title}', '{$point->freq}', '{$point->lat}', '{$point->lng}')";
        }

        // Then the ones not listed
        foreach ($navpoints as $point) {
            $insert[] = "(0, '{$point}', '{$point}', '0', '0', '0')";
        }

        $sql .= implode(',', $insert);
        DB::query($sql);

        return $return;
    }
}
