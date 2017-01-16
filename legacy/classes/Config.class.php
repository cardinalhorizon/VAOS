<?php

class Config {
    
    static $values = array();
    static $final = array();
    static $merge = array();


    /**
     * Add a configuration item. Alias of self::Add()
     *
     * @param mixed $name Name of the setting
     * @param mixed $value Value of the setting
     * @param bool $final Whether this can be changed later or not
     * @return none 
     *
     */
    public static function Add($name, $value, $final = false) {
        self::Set($name, $value, $final);
    }

    /**
     * Add a configuration item
     *
     * @param mixed $name Name of the setting
     * @param mixed $value Value of the setting
     * @param bool $final Whether this can be changed later or not
     * @return none 
     *
     */
    public static function Set($name, $value, $final = false) {
        
        if (in_array($name, self::$final))
            return;
        
        self::$values[$name] = $value;
        
        if ($final == true) {
            self::$final[] = $name;
        }
    }


    /**
     * Add a value to a setting which is an array
     *
     * @param string $name Setting name
     * @param string $key Key or value
     * @param mixed $value Value of the key to append
     * @return mixed This is the return value description
     *
     */
    public static function Append($name, $key = '', $value) {
        if (is_array(self::$values[$name]) == true) {
            if ($key == '')
                self::$values[$name][] = $value;
            else
                self::$values[$name][$key] = $value;
        }
    }

    /**
     * Get the type of a setting
     * 
     * @param string $name Name of the setting
     * @return string Returns string: array, object, float, int
     */
    public static function GetType($name) {
        if (is_array(self::$values[$name]))
            return 'array';
        elseif (is_object(self::$values[$name]))
            return 'object';
        elseif (is_float(self::$values[$name]))
            return 'float';
        elseif (is_int(self::$values[$name]))
            return 'int';
        else
            return 'string';
    }

    public static function Get($name, $key = '') {
        if ($key != '') {
            if (is_array(self::$values[$name]))
                return self::$values[$name][$key];
            elseif (is_object(self::$values[$name]))
                return self::$values[$name]->$key;
        }

        if (!isset(self::$values[$name]))
            return '';

        return self::$values[$name];
    }

    public static function Remove($name, $key = '') {
        if ($key != '')
            unset(self::$values[$name][$key]);
        else
            unset(self::$values[$name]);
    }

    /**
     * Load all the site settings. Make the settings into define()'s
     *	so they're accessible from everywhere
     */
    public static function LoadSettings() {
        while (list($key, $value) = each(self::$values)) {
            if (!is_array($value)) {
                if (!defined($key)) {
                    define($key, $value);
                }
            }
        }

        return true;
    }
}
