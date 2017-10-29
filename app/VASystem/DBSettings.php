<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/29/17
 * Time: 12:44 AM
 */

namespace App\VASystem;


use App\Models\Setting;

class DBSettings
{
    private $settingArray = array();

    public function __construct()
    {
        // Grab all the settings from the database
        $output = Setting::all();
        foreach ($output as $setting)
        {
            $values[$setting->key] = $setting->value;
        }
    }
    public function get($key)
    {
        try
        {
            $output = $this->settingArray[$key];
            return $output;
        }
        catch (Exception $e)
        {
            if(config('app.debug'))
            {
                dd($e);
            }
            else
            {
                report($e);
                return null;
            }
        }
    }
}