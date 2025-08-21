<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function activity()
    {
        return view('report.activity'); // resources/views/report/activity.blade.php
    }

    public function competitor()
    {
        return view('report.competitor'); // resources/views/report/competitor.blade.php
    }
}
