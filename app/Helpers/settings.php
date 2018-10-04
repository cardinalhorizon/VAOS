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

function groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
    $temp = array();
    foreach($arr as $key => $value) {
        //dd($value[$group]);
        $groupValue = $value[$group];
        if(!$preserveGroupKey)
        {
            unset($arr[$key][$group]);
        }
        if(is_object($groupValue))
        {
            if(!array_key_exists($groupValue->id, $temp)) {
                $temp[$groupValue->id] = array();
                $temp[$groupValue->id] = $groupValue->toArray();
            }
            $data = $arr[$key];
            $temp[$groupValue->id]['data'][] = $data;
        }
        else {
            if (!array_key_exists($groupValue, $temp)) {
                $temp[$groupValue] = array();
            }

            if (!$preserveSubArrays) {
                $data = count($arr[$key]) == 1 ? array_pop($arr[$key]) : $arr[$key];
            } else {
                $data = $arr[$key];
            }
            $temp[$groupValue][] = $data;
        }
    }
    return $temp;
}