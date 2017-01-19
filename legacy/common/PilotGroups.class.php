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

class PilotGroups extends CodonData {
    
    /**
     * Get all of the groups
     */
    public static function getAllGroups() {
        $all_groups = CodonCache::read('all_groups');

        if ($all_groups === false) {
            $sql = 'SELECT * 
					FROM ' . TABLE_PREFIX . 'groups
					ORDER BY `name` ASC';

            $all_groups = DB::get_results($sql);
            CodonCache::write('all_groups', $all_groups, 'medium');
        }

        return $all_groups;
    }

    /**
     * Add a group
     */
    public static function addGroup($groupname, $permissions) {
        
        $sql = "INSERT INTO ".TABLE_PREFIX."groups 
				(`name`, `permissions`, `core`) VALUES ('$groupname', $permissions, 0)";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_groups');

        return true;
    }

    /**
     * Save changes to a group
     * 
     * @param mixed $groupid
     * @param mixed $groupname
     * @param mixed $permissions
     * @return
     */
    public static function editGroup($groupid, $groupname, $permissions) {
        
        $groupid = intval($groupid);
        $groupname = DB::escape($groupname);

        $sql = 'UPDATE ' . TABLE_PREFIX . "groups
				SET `name`='$groupname', `permissions`=$permissions
				WHERE `groupid`=$groupid";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_groups');

        return true;
    }

    /**
     * Get information about a group
     * 
     * @param mixed $groupid
     * @return
     */
    public static function getGroup($groupid) {
        
        $groupid = intval($groupid);

        $sql = 'SELECT *
				FROM ' . TABLE_PREFIX . 'groups
				WHERE groupid=' . $groupid;

        return DB::get_row($sql);
    }

    /**
     * PilotGroups::getGroupID()
     * 
     * @param mixed $groupname
     * @return
     */
    public static function getGroupID($groupname) {
        
        if(is_object($groupname)) {
            $groupname = $groupname->friendlyname;
        }
        
        $sql = 'SELECT groupid FROM ' . TABLE_PREFIX . "groups
				WHERE name='{$groupname}'";

        $res = DB::get_row($sql);

        return $res->groupid;
    }

    /**
     * Add a user to a group, either supply the group ID or the name
     * 
     * @param mixed $pilotid
     * @param mixed $groupidorname
     * @return
     */
    public static function addUsertoGroup($pilotid, $groupidorname) {
               
        if ($groupidorname == '') {
            return false;
        }
       
        // If group name is given, get the group ID
        if (!is_numeric($groupidorname)) {
            $groupidorname = self::getGroupID($groupidorname);
        }

        if (self::checkUserInGroup($pilotid, $groupidorname) === true) {
            return true;
        }

        $sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (pilotid, groupid)
					VALUES ('.$pilotid.', '.$groupidorname.')';

        $res = DB::query($sql);
                
        if (DB::errno() != 0) {
            echo DB::error();
            return false;
        }

        return true;
    }

    /**
     * See if any group in the list has a certain permission
     * 
     * @param array $grouplist
     * @param int $perm
     * @return bool
     */
    public static function group_has_perm($grouplist, $perm) {
        return self::groupHasPermission($grouplist, $perm);
    }
    
    /**
     * See if any group in the list has a certain permission
     * 
     * @param array $grouplist
     * @param int $perm
     * @return bool
     */
    public static function groupHasPermission($grouplist, $perm) {
        
        if (!is_array($grouplist) || count($grouplist) == 0) {
            return false;
        }

        foreach ($grouplist as $group) {
            # Check zero (NO_ADMIN_ACCESS === 0)
            if ($group->permissions === NO_ADMIN_ACCESS) continue;

            # One of the group has full admin access
            if ((float)$group->permissions === (float)FULL_ADMIN) {
                return true;
            }

            # Check individually
            if (self::check_permission($group->permissions, $perm) == true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check permissions against integer set 
     * (bit compare, ($set & $perm) === $perm)
     *
     * @param int $set Permission set &
     * @param int $perm Permission (intval)
     * @return bool Whether it's set or not
     *
     */
    public static function check_permission($set, $perm) {
        return self::checkPermission($set, $perm);        
    }
    
    /**
     * Check permissions against integer set 
     * (bit compare, ($set & $perm) === $perm)
     *
     * @param int $set Permission set &
     * @param int $perm Permission (intval)
     * @return bool Whether it's set or not
     *
     */
    public static function checkPermission($set, $perm) {
        if (($perm & $set) === $perm) {
            return true;
        }

        return false;
    }


    /**
     * Set a permission ($set | $permission)
     *
     * @param int $set Integer set
     * @param int $perm Permission to remove
     * @return int New permission set
     *
     */
    public static function set_permission($set, $perm) {
        return $set | $perm;
    }


    /**
     * Remove permission from set ($set ^ $perm)
     *
     * @param int $set Permission set
     * @param int $perm Permission to remove
     * @return int New permission set
     *
     */
    public static function remove_permission($set, $perm) {
        $set = $set ^ $perm;
    }

    /**
     * Check if a user is in a group, pass the name or the id
     */
    public static function checkUserInGroup($pilotid, $groupid) {
        
        if (!is_numeric($groupid)) {
            $groupid = self::getGroupID($groupid);
        }

        $sql = 'SELECT g.groupid
    			 FROM ' . TABLE_PREFIX . 'groupmembers g
    			 WHERE g.pilotid=' . $pilotid . ' AND g.groupid=' . $groupid;

        if (!DB::get_row($sql)) {
            return false;
        }
        
        return true;
    }

    /**
     * PilotGroups::getUsersInGroup()
     * 
     * @param mixed $groupid
     * @return
     */
    public static function getUsersInGroup($groupid) {
        
        if (!is_numeric($groupid)) {
            $groupid = self::getGroupID($groupid);
        }

        $sql = 'SELECT p.*
				FROM ' . TABLE_PREFIX . 'groupmembers g
				INNER JOIN ' . TABLE_PREFIX . 'pilots p ON p.pilotid=g.pilotid
				WHERE g.groupid=' . $groupid;

        $ret = DB::get_results($sql);
        if(!$ret) {
            return array();
        }
        
        return $ret;
    }

    /**
     * The a users groups (pass their database ID)
     */
    public static function getUserGroups($pilotid) {
        $pilotid = DB::escape($pilotid);

        $sql = 'SELECT g.*
				FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
				WHERE u.pilotid=' . $pilotid . ' AND g.groupid=u.groupid';

        $ret = DB::get_results($sql);
        if(!$ret) {
            return array();
        }

        return $ret;
    }

    /**
     * Remove a user from a group (pass the ID or the name)
     */
    public static function RemoveUserFromGroup($pilotid, $groupid) {
        
        $pilotid = DB::escape($pilotid);
        $groupid = DB::escape($groupid);

        if (!is_numeric($groupid)) {
            $groupid = self::getGroupID($groupid);
        }

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'groupmembers
				WHERE pilotid=' . $pilotid . ' AND groupid=' . $groupid;

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * Remove a group
     */
    public static function RemoveGroup($groupid) {
        $groupid = DB::escape($groupid);

        //delete from groups table
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'groups WHERE groupid=' . $groupid;
        DB::query($sql);

        //delete from usergroups table
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'groupmembers WHERE groupid=' . $groupid;
        DB::query($sql);
    }
}
