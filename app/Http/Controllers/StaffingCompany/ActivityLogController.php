<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\ActivityLog as StaffingCompanyActivityLog;
use App\StaffingCompany\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        // if (Auth::user()->role != 'admin') {
        //     abort(403);
        // }
        $allActivityLogs = StaffingCompanyActivityLog::where('action', '!=', 'Error')->latest()->get();
        $errorLogs = StaffingCompanyActivityLog::where('action', 'Error')->latest()->get();

        return view('StaffingCompany.activicty.index', compact('allActivityLogs', 'errorLogs'));
    }
}

