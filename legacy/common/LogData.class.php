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

class LogData extends CodonData {

    public static function findLogs($params, $count = '', $start = '') {
        $sql = 'SELECT l.*, p.* 
				FROM ' . TABLE_PREFIX . 'adminlog l
				INNER JOIN ' . TABLE_PREFIX . 'pilots p ON l.pilotid = p.pilotid ';

        /* Build the select "WHERE" based on the columns passed */
        $sql .= DB::build_where($params);

        $sql .= ' ORDER BY l.datestamp DESC';

        if (strlen($count) != 0) {
            $sql .= ' LIMIT ' . $count;
        }

        if (strlen($start) != 0) {
            $sql .= ' OFFSET ' . $start;
        }

        $ret = DB::get_results($sql);
        return $ret;
    }

    public static function addLog($pilotid, $message) {
        $message = DB::escape($message);

        $sql = 'INSERT INTO ' . TABLE_PREFIX .
            "adminlog (`pilotid`, `datestamp`, `message`)
				VALUES ('{$pilotid}', NOW(), '{$message}')";

        DB::query($sql);
    }
}
