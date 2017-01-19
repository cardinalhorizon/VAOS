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

class RegistrationData extends CodonData {

    static public $salt;
    static public $error;
    static public $pilotid;

    /**
     * Get all of the custom fields that will show up
     *	during the registration
     */
    public static function getCustomFields($getall = false) {

        $sql = 'SELECT * 
                FROM `'.TABLE_PREFIX.'customfields`';

        if ($getall == false) {
            $sql .= ' WHERE `showonregister`=1';
        }

        return DB::get_results($sql);
    }

    /**
     * RegistrationData::CheckUserEmail()
     * 
     * @param mixed $email
     * @return
     */
    public static function checkUserEmail($email) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
					WHERE email=\'' . $email . '\'';

        return DB::get_row($sql);
    }

    /**
     * Add a  User
     * 
     * $data = array(
     * 'firstname' => '',
     * 'lastname' => '',
     * 'email' => '',
     * 'password' => '',
     * 'code' => '',
     * 'location' => '',
     * 'hub' => '',
     * 'confirm' => false);
     */
    public static function addUser($data) {

        /*$data = array(
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'password' => '',
        'code' => '',
        'location' => '',
        'hub' => '',
        'confirm' => false);*/

        $exists = self::CheckUserEmail($data['email']);
        if (is_object($exists)) {
            self::$error = 'Email already exists';
            return false;
        }

        //Set the password, add some salt
        $salt = md5(date('His'));
        $password = md5($data['password'] . $salt);

        //Stuff it into here, the confirmation email will use it.
        self::$salt = $salt;

        $code = DB::escape(strtoupper($data['code']));
        $firstname = DB::escape(ucwords($data['firstname']));
        $lastname = DB::escape(ucwords($data['lastname']));
        $location = DB::escape(strtoupper($data['location']));
        //Add this stuff in

        if ($data['confirm'] === true) $confirm = 1;
        else  $confirm = 0;

        $sql = "INSERT INTO " . TABLE_PREFIX . "pilots (firstname, lastname, email,
					code, location, hub, password, salt, confirmed, joindate, lastip)
				  VALUES (
                    '{$firstname}', '{$lastname}', '{$data['email']}', '{$code}',
					'{$location}', '{$data['hub']}', '{$password}', 
                    '{$salt}', {$confirm}, NOW(), 
                    '{$_SERVER['REMOTE_ADDR']}'
                    )";


        $res = DB::query($sql);
        if (DB::errno() != 0) {
            if (DB::errno() == 1062) {
                self::$error = 'This email address is already registered';
                return false;
            }

            self::$error = DB::error();
            return false;
        }

        //Grab the new pilotid, we need it to insert those "custom fields"
        $pilotid = DB::$insert_id;
        RanksData::CalculateUpdatePilotRank($pilotid);
        PilotData::generateSignature($pilotid);

        /* Add them to the default group */
        $defaultGroup = SettingsData::getSettingValue('DEFAULT_GROUP');
        PilotGroups::addUsertoGroup($pilotid, $defaultGroup);

        // For later
        self::$pilotid = $pilotid;

        //Get customs fields
        $fields = self::getCustomFields();
        
        if(count($fields) > 0) {
            foreach ($fields as $field) {
                $value = Vars::POST($field->fieldname);
                $value = DB::escape($value);
    
                if ($value != '') {
                    $sql = "INSERT INTO `".TABLE_PREFIX."fieldvalues` (fieldid, pilotid, value)
    							VALUES ($field->fieldid, $pilotid, '$value')";
    
                    DB::query($sql);
                }
            }
        }

        $pilotdata = PilotData::getPilotData($pilotid);
        
        /* Add this into the activity feed */
        $message = Lang::get('activity.new.pilot');
        foreach($pilotdata as $key=>$value) {
            $message = str_replace('$'.$key, $value, $message);
        }
        
        # Add it to the activity feed
        ActivityData::addActivity(array(
            'pilotid' => $pilotid,
            'type' => ACTIVITY_NEW_PILOT,
            'refid' => $pilotid,
            'message' => htmlentities($message),
        ));        

        return true;
    }

    /**
     * RegistrationData::ChangePassword()
     * 
     * @param mixed $pilotid
     * @param mixed $newpassword
     * @return
     */
    public static function ChangePassword($pilotid, $newpassword) {
        $salt = md5(date('His'));
        $password = md5($newpassword . $salt);
        self::$salt = $salt;

        //, confirmed='y'
        $sql = "UPDATE " . TABLE_PREFIX . "pilots 
				SET password='$password', 
					salt='$salt'
				WHERE pilotid=$pilotid";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * RegistrationData::SendEmailConfirm()
     * 
     * @param mixed $email
     * @param mixed $firstname
     * @param mixed $lastname
     * @param string $newpw
     * @return void
     */
    public static function SendEmailConfirm($email, $firstname, $lastname, $newpw = '') {
        
        $confid = self::$salt;

        $subject = SITE_NAME . ' Registration';

        Template::Set('firstname', $firstname);
        Template::Set('lastname', $lastname);
        Template::Set('confid', $confid);
        
        $oldPath = Template::setTemplatePath(TEMPLATES_PATH);
        $oldSkinPath = Template::setSkinPath(ACTIVE_SKIN_PATH);
        
        $message = Template::getTemplate('email_registered.tpl', true, true, true);
        
        Template::setTemplatePath($oldPath);
        Template::setSkinPath($oldSkinPath);

        //email them the confirmation
        Util::sendEmail($email, $subject, $message);
    }

    /**
     * RegistrationData::ValidateConfirm()
     * 
     * @return
     */
    public static function ValidateConfirm() {
        $confid = Vars::GET('confirmid');

        $sql = "UPDATE `".TABLE_PREFIX."pilots` 
                SET `confirmed`=1, `retired`=0 
                WHERE `salt`='".$confid."'";
        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }
}
