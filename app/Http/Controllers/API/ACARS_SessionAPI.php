<?php

namespace App\Http\Controllers\API;

use App\ACARS_Session;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * ACARS Global Session Management
 * @package App\Http\Controllers\API
 */
class ACARS_SessionAPI extends Controller
{
    public function addSession(Request $request)
    {
        $acars = new ACARS_Session();

    }
}
