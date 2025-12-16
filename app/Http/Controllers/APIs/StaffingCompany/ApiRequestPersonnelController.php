<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffingCompany\Contact;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeFunction;
use App\Models\StaffingCompany\RequestPersonnel;

class ApiRequestPersonnelController extends Controller
{

    public function index()
    {
        $user = Contact::where('user_id', Auth::id())->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access',
            ], 401);
        }
        $request_personnels = RequestPersonnel::with('supervisor')
            ->where('temp_sup_id',$user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Request personnels fetched successfully',
            'request_personnels' => $request_personnels
        ]);
    }


    public function projectsList()
    {
        $projects = StaffingProject::with('projectPlannings')->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'message' => 'Active projects fetched successfully',
            'projects' => $projects
        ]);
    }
    public function employeeFunctions()
    {
        $functions = EmployeeFunction::orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'message' => 'Employee functions fetched successfully',
            'functions' => $functions
        ]);
    }
    public function store(Request $request)
{
    DB::beginTransaction();
    try {
        $application_no = RequestPersonnel::count() + 1;

        $request_personnel = new RequestPersonnel();
        $request_personnel->project_id = $request->project;
        $request_personnel->temp_sup_id = $request->temp_sup_id; // fixed key
        $request_personnel->application_no = 'AP-' . $application_no;
        $request_personnel->adopted_by = $request->adopted_by;
        $request_personnel->complete = $request->complete;

        // safer Carbon parsing
        $request_personnel->application_date_time = Carbon::parse($request->application_date_time);
        $request_personnel->starting_date_time = Carbon::parse($request->starting_date_time);

        $request_personnel->no_of_people = $request->no_of_people;
        $request_personnel->days = $request->how_many_days;
        $request_personnel->report_to = $request->report_to;
        $request_personnel->mobile = $request->mobile;
        $request_personnel->requirements = $request->requirements;
        $request_personnel->notes = $request->notes;
        $request_personnel->comments = $request->comments;
        $request_personnel->status = 0; // added back
        $request_personnel->save();

        if ($request->has('activities') && method_exists($request_personnel, 'employeeFunction')) {
            $request_personnel->employeeFunction()->attach($request->activities);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Request personnel created successfully',
        ]);

    } catch (\Throwable $throwable) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => $throwable->getMessage(),
        ]);
    }
}
}
