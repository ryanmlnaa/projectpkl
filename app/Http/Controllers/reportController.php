<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competitor;

class ReportController extends Controller
{
    public function activity()
    {
        return view('report.activity'); // resources/views/report/activity.blade.php
    }

    public function competitor()
    {
        // 🔹 PERUBAHAN: ambil data competitor dari database
        $competitors = Competitor::latest()->get();

        // 🔹 PERUBAHAN: kirim variable $competitors ke view
        return view('report.competitor', compact('competitors')); 
    }
}