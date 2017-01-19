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

class CronData extends CodonData {

    /**
     * Checks the last update time for a given event
     * Returns the difference in days and in time
     *
     */
    public static function check_lastupdate($name) {
        $name = strtoupper($name);
        $sql = 'SELECT *, DATEDIFF(NOW(), lastupdate) AS days,
						  TIMEDIFF(NOW(), lastupdate) as timediff
				 FROM `' . TABLE_PREFIX . "updates`
				 WHERE `name`='{$name}'";


        $ret = DB::get_row($sql);
        return $ret;
    }

    public static function check_hoursdiff($name, $age_hours) {
        $name = strtoupper($name);
        $sql = 'SELECT `lastupdate`
				FROM ' . TABLE_PREFIX . "updates
				WHERE DATE_SUB(CURDATE(), INTERVAL {$age_hours} HOUR) <= lastupdate
					AND name='{$name}'";

        $row = DB::get_row($sql);
        if (!$row) {
            return false;
        }

        return true;
    }


    /**
     * Sets the last update time for an event to NOW()
     *
     */
    public static function set_lastupdate($name) {
        $name = strtoupper($name);
        if (!self::check_lastupdate($name)) {
            $sql = "INSERT INTO " . TABLE_PREFIX . "updates
							(name, lastupdate)
					VALUES	('{$name}', NOW())";
        } else {
            $sql = "UPDATE " . TABLE_PREFIX . "updates
						SET lastupdate=NOW()
						WHERE name='{$name}'";
        }

        DB::query($sql);
    }
}
