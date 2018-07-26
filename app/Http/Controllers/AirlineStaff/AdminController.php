<?php

namespace App\Http\Controllers\AirlineStaff;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
