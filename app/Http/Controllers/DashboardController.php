<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CounterQueue;
use App\Models\CounterRegistration;
use App\Models\Counter;

class DashboardController extends Controller
{
    public function __construct()
    {
       date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        return view('admin.dashboard.index');
    }
}
