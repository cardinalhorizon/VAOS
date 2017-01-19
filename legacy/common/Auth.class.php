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

class Auth extends CodonData {
    
    public static $init = false;
    public static $loggedin = false;
    public static $error_message;

    public static $pilotid;
    public static $userinfo;
    public static $pilot;
    public static $session_id;
    public static $usergroups;

    /**
     * Start the "auth engine", see if anyone is logged in and grab their info
     *
     * @return mixed This is the return value description
     *
     */
    public static function StartAuth() {
        self::$init = true;
        self::$session_id = SessionManager::Get('session_id');

        $assign_id = false;

        if (self::$session_id == '') {
            if ($_COOKIE[VMS_AUTH_COOKIE] != '') {
                $data = explode('|', $_COOKIE[VMS_AUTH_COOKIE]);
                $session_id = $data[0];
                $pilot_id = $data[1];
                $ip_address = $data[2];

                // TODO: Determine data reliability from IP addresses marked
                $session_info = self::get_session($session_id, $pilot_id, $ip_address);

                if ($session_info) {
                    /* Populate session info */
                    $userinfo = PilotData::GetPilotData($pilot_id);

                    if (!$userinfo) {
                        self::$loggedin = false;
                        return false;
                    }

                    self::$loggedin = true;
                    self::$userinfo = $userinfo;
                    self::$pilot = $userinfo;
                    self::$pilotid = self::$userinfo->pilotid;
                    self::$usergroups = SessionManager::Get('usergroups');
                    self::$session_id = $session_id;

                    if (self::$usergroups == '') {
                        self::$usergroups = PilotGroups::GetUserGroups($userinfo->pilotid);
                    }

                    SessionManager::Set('loggedin', true);
                    SessionManager::Set('userinfo', $userinfo);
                    SessionManager::Set('usergroups', self::$usergroups);
                    PilotData::UpdateLogin($userinfo->pilotid);

                    self::update_session(self::$session_id, self::$userinfo->pilotid);

                    return true;
                }
            }

            // Look for an existing session based on ID
            // No session ID was found anywhere so assign one
            $assign_id = true;
            self::$session_id = self::start_session(0);
            SessionManager::Set('session_id', self::$session_id);
        } else {
            // There's a session ID, so double check that they're logged in
            if (SessionManager::Get('loggedin') == true) {
                self::$loggedin = true;
                self::$userinfo = SessionManager::Get('userinfo');
                self::$pilot = self::$userinfo;

                self::$usergroups = PilotGroups::GetUserGroups(self::$userinfo->pilotid);
                self::$pilotid = self::$userinfo->pilotid;

                # Bugfix, in case user updates their profile info, grab the latest
                self::$userinfo = PilotData::GetPilotData(self::$pilotid);
                self::$pilot = self::$userinfo;
                self::update_session(self::$session_id, self::$userinfo->pilotid);

                return true;
            } else {
                // Already been assigned a session ID, and not signed in...
                self::$loggedin = false;
                self::update_session(self::$session_id, 0);
                $assign_id = false;
            }
        }

        // Empty session so start one up, and they're not logged in
        if ($assign_id == true) {

        }

        return true;
    }

    public static function start_session($pilot_id) {
        $sql = "INSERT INTO " . TABLE_PREFIX . "sessions
				   (`pilotid`, `ipaddress`, `logintime`)
				   VALUES ({$pilot_id},'{$_SERVER['REMOTE_ADDR']}', NOW())";

        DB::query($sql);
        $session_id = DB::$insert_id;

        return $session_id;
    }


    public static function update_session($session_id, $pilot_id) {
        $sql = 'UPDATE ' . TABLE_PREFIX . "sessions
			    SET `pilotid`={$pilot_id}, `logintime`=NOW(), `ipaddress`='{$_SERVER['REMOTE_ADDR']}'
			    WHERE `id`={$session_id}";

        DB::query($sql);
        $session_id = $session_data->id;
    }

    public static function get_session($session_id, $pilot_id, $ip_address) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . "sessions
				WHERE id = '{$session_id}' AND pilotid = '{$pilot_id}'
			   "; //AND ipaddress = '{$ip_address}'

        $results = DB::get_row($sql);
        return $results;
    }

    public static function remove_sessions($pilot_id) {
        $sql = "DELETE FROM " . TABLE_PREFIX . "sessions
				WHERE pilotid={$pilot_id}";

        DB::query($sql);
    }


    /**
     *  Clear any guest sessions which have expired
     *
     * @return mixed This is the return value description
     *
     */
    public static function clearExpiredSessions() {
        $time = Config::Get('SESSION_GUEST_EXPIRE');
        $sql = "DELETE FROM " . TABLE_PREFIX . "sessions 
				WHERE DATE_SUB(NOW(), INTERVAL {$time} MINUTE) > `logintime`
					AND `pilotid` = 0";

        DB::query($sql);
    }

    /**
     * Return the pilot ID of the currently logged in user
     *
     * @return int The pilot's ID
     *
     */
    public static function PilotID() {
        return self::$userinfo->pilotid;
    }

    /**
     * Get their firstname/last name
     */
    public static function DisplayName() {
        return self::$userinfo->firstname . ' ' . self::$userinfo->lastname;
    }

    /**
     * Return true/false if they're logged in or not
     */
    public static function LoggedIn() {
        if (self::$init == false) {
            return self::StartAuth();
        }

        return self::$loggedin;
    }

    /**
     * See if a use is in a given group
     */
    public static function UserInGroup($groupname) {
        if (!self::LoggedIn()) return false;

        if (!self::$usergroups) self::$usergroups = array();
        foreach (self::$usergroups as $group) {
            if ($group->name == $groupname) return true;
        }

        return false;
    }

    /**
     * Log the user in
     */
    public static function ProcessLogin($useridoremail, $password) {
        # Allow them to login in any manner:
        #  Email: blah@blah.com
        #  Pilot ID: VMA0001, VMA 001, etc
        #  Just ID: 001
        if (is_numeric($useridoremail)) {
            $useridoremail = $useridoremail - intval(Config::Get('PILOTID_OFFSET'));
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
				   WHERE pilotid=' . $useridoremail;
        } else {
            # They're logging in with an email
            if (preg_match('/^.*\@.*$/i', $useridoremail) > 0) {
                $emailaddress = DB::escape($useridoremail);
                $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
						WHERE email=\'' . $useridoremail . '\'';
            }
            # They're loggin in with a pilot id
            elseif (preg_match('/^([A-Za-z]*)(.*)(\d*)/', $useridoremail, $matches) > 0) {
                $id = trim($matches[2]);
                $id = $id - intval(Config::Get('PILOTID_OFFSET'));

                $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
						WHERE pilotid=' . $id;
            }
            # No idea
            else {
                self::$error_message = 'Invalid user ID';
                return false;
            }
        }

        $password = DB::escape($password);
        $userinfo = DB::get_row($sql);

        if (!$userinfo) {
            self::$error_message = 'This user does not exist';
            return false;
        }

        /*  Implement the pilot statuses, see if they are allowed in
            according to their status */
        $pilotStatuses = Config::get('PILOT_STATUS_TYPES');
        foreach($pilotStatuses as $id => $info) {
            if($userinfo->retired == $id && $info['canlogin'] == false) {
                self::$error_message = $info['message'];
                return false;
            }
        }
        
        /*if($userinfo->retired == 1)
        {
        self::$error_message = 'Your account was deactivated, please contact an admin';
        return false;
        }*/

        //ok now check it
        $hash = md5($password . $userinfo->salt);

        if ($hash == $userinfo->password) {
            
            self::$userinfo = $userinfo; #deprecated
            self::$pilot = self::$userinfo;

            self::update_session(self::$session_id, self::$userinfo->pilotid);

            SessionManager::Set('loggedin', 'true');
            SessionManager::Set('userinfo', $userinfo);
            SessionManager::Set('usergroups', PilotGroups::GetUserGroups($userinfo->pilotid));

            PilotData::updateProfile($pilotid, array('lastlogin' => 'NOW()', 'lastip' => $_SERVER['REMOTE_ADDR'], ));

            return true;
        } else {
            self::$error_message = 'Invalid login, please check your username and password';
            self::LogOut();

            return false;
        }
    }

    /**
     * Log them out
     */
    public static function LogOut() {
        #self::remove_sessions(SessionManager::GetValue('userinfo', 'pilotid'));

        # Mark them as guest
        self::update_session(self::$session_id, 0);

        # "Ghost" entry
        //self::start_session(self::$userinfo->pilotid); // Orphaned?

        SessionManager::Set('loggedin', false);
        SessionManager::Set('userinfo', '');
        SessionManager::Set('usergroups', '');

        # Delete cookie
        $_COOKIE[VMS_AUTH_COOKIE] = '';
        setcookie(VMS_AUTH_COOKIE, false);

        self::$loggedin = false;
    }
}
