<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 1/6/17
 * Time: 3:29 AM
 */

namespace App\Classes;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class VAOS_ImportExport
{
    public static function importFleet(AircraftListExcel $file)
    {
        Excel::load($file, function($reader) {})->get();
    }
}