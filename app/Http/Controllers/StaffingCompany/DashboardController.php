<?php

namespace App\Http\Controllers\StaffingCompany;

use Auth;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\EmployAgency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\OrderContainer;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\SfWeekCard;

class DashboardController extends Controller
{
    public function index()
    {
        $lang = Auth::user()->language ?? 'en';
        App::setLocale($lang);
        session()->put("locale", $lang);

        $now = Carbon::now('Europe/Amsterdam');
        $nineHoursAgo = $now->copy()->subHours(9);


        $currentWeek = Carbon::now()->format('oW');
        $week = substr($currentWeek, 4);

        $currentYear = Carbon::now()->year;
        $currWeekNum = Carbon::now()->weekOfYear;


        $plannings = ProjectPlanning::where('week_no', $currentWeek)
            ->whereYear('date', $currentYear)
            ->with(['employeeProjects', 'staffingProjects'])
            ->latest()
            ->get();

        $activeProjectsCount = StaffingProject::where('active', 1)->count();


        $project_planning_ids = ProjectPlanning::where('week_no', $currentYear . $currWeekNum)
            ->whereNull('deleted_at')
            ->pluck('id');
        $project_plannings = ProjectPlanning::whereIn('id', $project_planning_ids)->get();
        $counts = [];
        $planningProjectsCount = 0;

        foreach ($project_plannings as $pp) {
            $count = $pp->staffingProjects()->count();
            $counts[$pp->id] = $count;
            $planningProjectsCount += $count;
        }

        $assignedPersonnelIds = EmployeeProjectPlanning::where('week_no', $currentWeek)
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        $assignedPersonnelCount = count($assignedPersonnelIds);
        $loggedInCount = $assignedPersonnelCount;
        $notLoggedInCount = 0;
        $orderContainers = OrderContainer::with(['project', 'supplier'])->latest()->get();
        $allStatuses = [
            'open',
            'in progress',
            'pick up',
            'picked',
            'canceled',
            'close',
            'send to supplier',
        ];
        $orderContainersStatus = OrderContainer::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusCount = [];
        foreach ($allStatuses as $status) {
            $statusCount[$status] = $orderContainersStatus[$status] ?? 0;
        }
        $labels = array_keys($statusCount);
        $values = array_values($statusCount);
        $totalCount = array_sum($statusCount);

        $openOrders = OrderContainer::where('status', 'open')->get();

        $data = [
            'staff' => Personnel::count(),
            'projects' =>  $planningProjectsCount,
            'agencies' => EmployAgency::count(),
            'customers' => Customer::where('company_id', _user()->company_id)->count(),
            'loggedInCount' => $loggedInCount,
            'activeProjects' => $activeProjectsCount,
            'notLoggedInCount' => $notLoggedInCount,
            'assignedPersonnel' => $assignedPersonnelCount,
            'currentWeek' => $currentWeek,
            'statusCount' => $statusCount,
            'totalCount' => $totalCount,
            'week' => $week,
        ];

        $total = SfWeekCard::where('week_no', $currentWeek)->count();
        $approved = SfWeekCard::where('week_no', $currentWeek)
            ->where('time_approve', 1)->count();
        $notApproved = SfWeekCard::where('week_no', $currentWeek)
            ->where('time_approve', 0)->count();

        return view('StaffingCompany.index', compact('data', 'orderContainers', 'labels', 'values', 'openOrders', 'total', 'approved', 'notApproved'));
    }
}
