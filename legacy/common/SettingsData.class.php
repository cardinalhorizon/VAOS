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

class SettingsData extends CodonData {

    /**
     * Return all settings
     * 
     * @return
     */
    public static function getAllSettings() {
        $all_settings = CodonCache::read('site_settings');
        if ($all_settings === false) {
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'settings';
            $all_settings = DB::get_results($sql);

            CodonCache::write('site_settings', $all_settings, 'long');
        }

        return $all_settings;
    }

    /**
     * Get a specific setting
     * 
     * @param mixed $name
     * @return
     */
    public static function getSetting($name) {
        return DB::get_row('SELECT * FROM ' . TABLE_PREFIX . 'settings 
					WHERE name=\'' . $name . '\'');
    }

    public static function getSettingValue($name) {
        $ret = DB::get_row('SELECT value FROM ' . TABLE_PREFIX . 'settings 
					WHERE name=\'' . $name . '\'');

        return $ret->value;
    }


    /**
     * Return all of the custom fields data
     */
    public static function getAllFields() {
        $all_fields = CodonCache::read('allfields');
        if ($all_fields === false) {
            $all_fields = DB::get_results('SELECT * FROM ' . TABLE_PREFIX . 'customfields');
            CodonCache::write('allfields', $all_fields, 'long');
        }

        return $all_fields;
    }


    public static function getField($fieldid) {
        $fieldid = intval($fieldid);
        return DB::get_row('SELECT * FROM ' . TABLE_PREFIX .
            'customfields WHERE fieldid=' . $fieldid);
    }

    /**
     * Edit a custom field to be used in a profile
     * 
     * $data= array('fieldid'=>,
     * 'title'=>,
     * 'value'=>,
     * 'type'=>,
     * 'public'=>,
     * 'showinregistration'=>);
     */
    public static function addField($data) {
        $fieldname = str_replace(' ', '_', $data['title']);
        $fieldname = strtoupper($fieldname);

        //Check, set up like this on purpose to default "safe" values
        if ($data['public'] == true) $data['public'] = 1;
        else  $data['public'] = 0;

        if ($data['showinregistration'] == true) $data['showinregistration'] = 1;
        else  $data['showinregistration'] = 0;

        $data['type'] = strtolower($data['type']);

        $sql = "INSERT INTO " . TABLE_PREFIX .
            "customfields (title, fieldname, value, type, public, showonregister)
					VALUES ('{$data['title']}', '$fieldname', '{$data['value']}', '{$data['type']}', {$data['public']}, {$data['showinregistration']})";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('allfields');

        return true;
    }

    /**
     * Edit a custom field to be used in a profile
     * 
     * $data= array('fieldid'=>,
     * 'title'=>,
     * 'value'=>,
     * 'type'=>,
     * 'public'=>,
     * 'showinregistration'=>);
     */
    public static function editField($data) {

        $fieldname = strtoupper(str_replace(' ', '_', $data['title']));

        //Check, set up like this on purpose to default "safe" values
        if ($data['public'] == true) $data['public'] = 1;
        else  $data['public'] = 0;

        if ($data['showinregistration'] == true) $data['showinregistration'] = 1;
        else  $data['showinregistration'] = 0;

        $data['type'] = strtolower($data['type']);

        $sql = "UPDATE " . TABLE_PREFIX . "customfields
				SET title='{$data['title']}', fieldname='{$fieldname}', value='{$data['value']}',
					type='{$data['type']}', public={$data['public']}, 
					showonregister={$data['showinregistration']}
				WHERE fieldid={$data['fieldid']}";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('allfields');

        return true;
    }

    /**
     * Save site settings
     *
     * @param string $name Setting name. Must be unique
     * @param string $value Value of the setting
     * @param boolean $core Whether it's "vital" to the engine or not. Bascially blocks deletion
     */
    public static function saveSetting($name, $value, $descrip = '', $core = false) {
        if (is_bool($value)) {
            if ($value == true) {
                $value = 'true';
            } elseif ($value == false) {
                $value = 'false';
            }
        }

        //see if it's an update
        if ($core == true) $core = 't';
        else  $core = 'f';

        $name = strtoupper(DB::escape($name));
        $value = DB::escape($value);
        $descrip = DB::escape($descrip);

        $sql = 'UPDATE ' . TABLE_PREFIX . 'settings
					SET value=\'' . $value . '\' WHERE name=\'' . $name . '\'';

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('site_settings');

        return true;
    }

    /**
     * See if the setting is part of the core
     */
    public static function isCoreSetting($setting_name) {
        $sql = 'SELECT core FROM ' . TABLE_PREFIX . 'settings WHERE name=\'' . $setting_name .
            '\'';
        $res = DB::get_row($sql);

        if (!$res) return false;


        if ($res->core == 't') {
            return true;
        }

        return false;
    }

    public static function deleteField($id) {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'customfields WHERE `fieldid`=' . $id;
        $res = DB::query($sql);

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'fieldvalues WHERE `fieldid`=' . $id;
        $res = DB::query($sql);

        CodonCache::delete('allfields');
    }
}
