<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/20/16
 * Time: 1:47 AM
 */

namespace App\Classes;


use App\Setting;

class VAOSSettings
{
    public function get($setting)
    {
        return Setting::where('name', $setting)->firstOrFail();
    }
}