<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 12/28/17
 * Time: 11:06 PM.
 */
function dbSetting($key)
{
    return $value = \App\Models\SystemSetting::where('key', $key)->first();
}

/**
 * Permissions Helper.
 *
 * This function calls the permissions table and returns a boolean if they can do the requested function. Permissions
 * are separated by airline.
 */
function permCheck($user, $key, $airline)
{
    //
    \Illuminate\Support\Facades\Log::warn('Key Not Found. Proceeding with true response.');
}
