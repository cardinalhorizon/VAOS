<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    private $settingArray = [];

    public function __construct()
    {
        // Grab all the settings from the database
        $output = Setting::all();
        foreach ($output as $setting) {
            $values[$setting->key] = $setting->value;
        }
    }

    public function get($key)
    {
        try {
            $output = $this->settingArray[$key];

            return $output;
        } catch (Exception $e) {
            if (config('app.debug')) {
                dd($e);
            } else {
                report($e);

                return null;
            }
        }
    }
}
