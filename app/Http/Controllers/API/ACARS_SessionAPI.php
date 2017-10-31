<?php

namespace App\Http\Controllers\API;

use App\ACARS_Session;
use Illuminate\Http\Request;

/**
 * ACARS Global Session Management.
 */
class ACARS_SessionAPI extends Controller
{
    public function addSession(Request $request)
    {
        $acars = new ACARS_Session();
    }
}
