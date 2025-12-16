<?php

namespace App\Http\Controllers\StaffingCompany;

use PDF;
use Carbon\Carbon;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeFunction;
use App\Models\StaffingCompany\RequestPersonnel;

class RequestPersonnelController extends Controller
{
    public function index()
    {
        $requested_personnels = RequestPersonnel::all()->sortByDesc('created_at');
        return view('StaffingCompany.RequestPersonnel.index')->with('request_personnels', $requested_personnels);
    }
    public function create()
    {
        $e_functions = EmployeeFunction::orderBy('name')->get();
        //$projects = StaffingProject::orderBy('name')->get();
        $projects = StaffingProject::where('active', true) // Fetch only active projects
            ->orderBy('name') // Order by name
            ->get(); // Retrieve the results

        $translations = __('Staffing_Company/Request_Staff/crud');
        $common = __('Staffing_Company/common');

        return view('StaffingCompany.RequestPersonnel.create', compact('e_functions', 'projects', 'translations', 'common'));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $application_no = RequestPersonnel::count() + 1;
            $request_personnel = new RequestPersonnel();
            $request_personnel->project_id = $request->project;
            $request_personnel->temp_sup_id = $request->temp_performer;
            $request_personnel->application_no = 'AP-' . $application_no;
            $request_personnel->adopted_by = $request->adopted_by;
            $request_personnel->complete = $request->complete;
            $request_personnel->application_date_time = $this->dateTimeFormat($request->application_date_time);
            $request_personnel->starting_date_time = $this->dateTimeFormat($request->starting_date_time);
            $request_personnel->no_of_people = $request->no_of_people;
            $request_personnel->days = $request->how_many_days;
            $request_personnel->report_to = $request->report_to;
            $request_personnel->mobile = _formatPhoneNumber($request->mobile);
            $request_personnel->requirements = $request->requirements;
            $request_personnel->notes = $request->notes;
            $request_personnel->comments = $request->comments;
            $request_personnel->status = 0;
            $request_personnel->save();

            // Attach activities
            if ($request->has('activities')) {
                $request_personnel->employeeFunction()->attach($request->activities);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Staffing_Company/Request_Staff/crud.request_staff_create')
            ]);
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return $this->sendError(__('messages.invalid_input'));
        }
    }

    public function approve($id)
{
    $requestPersonnel = RequestPersonnel::findOrFail($id);
    $requestPersonnel->status = 1; // Approved
    $requestPersonnel->save();
    return redirect()->back()->with('message', 'Request approved successfully');
}

    public function show($id)
    {
        // return response()->json(RequestPersonnel::with(['project.projectPerformer', 'employeeFunction'])->find($id));
        $requestPersonnel = RequestPersonnel::with(['project.projectPerformer', 'employeeFunction'])->findOrFail($id);

        return response()->json(array_merge(
            $requestPersonnel->toArray(),
            ['function_ids' => $requestPersonnel->employeeFunction->pluck('id')->toArray()]
        ));
    }

    public function view($id)
    {

        $common = __('Staffing_Company/common');
        $translations = __('Staffing_Company/Request_Staff/crud');
        return view('StaffingCompany.RequestPersonnel.show', compact('id', 'common', 'translations'));
    }

    public function edit($id)
    {
        $e_functions = EmployeeFunction::orderBy('name')->get();
        $translations = __('Staffing_Company/Request_Staff/crud');
        $common = __('Staffing_Company/common');
        return view('StaffingCompany.RequestPersonnel.update', compact('id', 'e_functions', 'translations', 'common'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $requestPersonnel = RequestPersonnel::findOrFail($id);

            $requestPersonnel->update([
                'mobile' => _formatPhoneNumber($request->mobile),
                'complete' => $request->complete,
                'adopted_by' => $request->adopted_by,
                'application_date_time' => $this->dateTimeFormat($request->application_date_time),
                'starting_date_time' => $this->dateTimeFormat($request->starting_date_time),
                'no_of_people' => $request->no_of_people,
                'days' => $request->how_many_days,
                'requirements' => $request->requirements,
                'notes' => $request->notes,
                'comments' => $request->comments,
            ]);

            $validActivities = collect($request->activities)
                ->filter()
                ->unique()
                ->toArray();

            $requestPersonnel->employeeFunction()->sync($validActivities);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('Staffing_Company/Request_Staff/crud.request_staff_update')
            ]);
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => __('messages.invalid_input')
            ], 500);
        }
    }


    public function destroy(RequestPersonnel $request_personnel)
    {
        $request_personnel->delete();

        return redirect()->back()->with('message', 'Request Personnel Deleted Successfully!');
    }

    public function dateTimeFormat($date_time)
    {
        $date = Carbon::parse($date_time)->format('Y-m-d');
        $time = Carbon::parse($date_time)->format('H:i:s');

        return $date . ' ' . $time;
    }

    public function downloadPDF($id)
    {
        $requested_personnel = RequestPersonnel::with(['project.projectPerformer', 'employeeFunction', 'supervisor'])->find($id);
        $project = $requested_personnel->project;
        $supervisor = $requested_personnel->supervisor;

        if (empty($requested_personnel)) {
            return redirect('admin/404');
        }

        $pdf = PDF::loadView('StaffingCompany.RequestPersonnel.pdf', compact('requested_personnel', 'project', 'supervisor'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Aanvraag_Personeel_AP-00' . $id . '.pdf');
    }
}
