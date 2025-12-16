<?php

namespace App\Http\Controllers\StaffingCompany;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\WeekState;
use App\Models\StaffingCompany\SCTimeCard;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\StaffGroup;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeFunction;
use App\Models\StaffingCompany\EmployeeProjectPlanning;

class EmployeeProjectPlanningController extends Controller
{

    private function updateWeekCardOnAdd($employeeId, $projectId, $week_no, $dayNo)
    {
        $weekState = WeekState::where('week_no', $week_no)
            ->whereHas('staffingProjects', function ($q) use ($projectId) {
                $q->where('staffing_project_id', $projectId);
            })
            ->first();

        if (!$weekState) return;

        $week_card = SfWeekCard::firstOrCreate(
            [
                'week_state_id' => $weekState->id,
                'personnel_id'  => $employeeId,
                'week_no'       => $week_no
            ],
            [
                'hours_1' => 0,
                'hours_2' => 0,
                'hours_3' => 0,
                'hours_4' => 0,
                'hours_5' => 0,
                'hours_6' => 0,
                'hours_7' => 0
            ]
        );


        for ($i = $dayNo; $i <= 5; $i++) {
            $week_card->{'hours_' . $i} = 8;
        }


        $week_card->save();

        // update total
        $week_card->total_hours = collect(range(1, 7))
            ->sum(fn($d) => $week_card->{'hours_' . $d});

        $week_card->save();
    }



    public function create(Request $request)
    {
        $e_functions = EmployeeFunction::orderBy('name')->get();
        $projects = StaffingProject::orderBy('name')->get();
        $personnels = Personnel::where('active', 1)->orderBy('first_name')->get();
        $groups = StaffGroup::all();
        $planning_id = $request->planning_id;
        $date = $request->date;
        $project_id = $request->project_id;
        $translations = __('Staffing_Company/common');
        return view(
            'StaffingCompany.ProjectPlanning.create',
            compact('projects', 'e_functions', 'personnels', 'planning_id', 'date', 'project_id', 'groups', 'translations')
        );
    }
    public function store(Request $request)
    {
        $employeeId = $request->personnel;
        $projectId  = $request->project_id;
        $planDate   = $request->plan_date;
        $status     = $request->p_status;

        $carbon     = Carbon::parse($planDate);

        $existsActive = EmployeeProjectPlanning::where([
            'employee_id' => $employeeId,
            'project_id'  => $projectId,
            'date'        => $planDate,
            'status'      =>    $status
        ])->exists();

        if ($existsActive) {
            return response()->json([
                'message' => "Employee is already assigned to this project on " . $planDate,
                'status' => false
            ], 200);
        }


        $existsRemoved = EmployeeProjectPlanning::where([
            'employee_id' => $employeeId,
            'project_id'  => $projectId,
            'date'        => $planDate
        ])->first();

        $project_planning_id = ProjectPlanning::where('date', $planDate)
            ->latest()
            ->value('id');

        if ($existsRemoved) {
            $existsRemoved->update([
                'status'   =>  $status,
                'geschikt' => $request->employee_function,
                'group_id' => $request->group,
                'notes'    => $request->comments,
            ]);

            return response()->json([
                'message' => "Employee was removed earlier and is now re-added.",
                'status' => true
            ], 200);
        }


        EmployeeProjectPlanning::create([
            'project_planning_id' => $project_planning_id,
            'employee_id' => $employeeId,
            'project_id' => $projectId,
            'week_no' => $carbon->format('oW'),
            'geschikt' => $request->employee_function,
            'status' => $status,
            'group_id' => $request->group,
            'notes' => $request->comments,
            'date' => $planDate,
        ]);

        return response()->json([
            'message' => __('Staffing_Company/Project_Planning/p_index.staff_added'),
            'status' => true
        ], 200);
    }


    public function show($id)
    {
        return response()->json(EmployeeProjectPlanning::with('personnel')->find($id));
    }

    public function edit($id)
    {
        $e_functions = EmployeeFunction::all();
        $groups = StaffGroup::all();
        $personnels = Personnel::orderBy('first_name')->get();
        $translations = __('Staffing_Company/common');
        $planning = EmployeeProjectPlanning::with('personnel')->find($id);

        return view('StaffingCompany.ProjectPlanning.update', compact('planning', 'personnels', 'e_functions', 'groups', 'translations'));
    }
    public function update(Request $request, $id)
    {
        EmployeeProjectPlanning::where('id', $id)->update([
            'geschikt' => $request->employee_function,
            'employee_id' => $request->personnel,
            'group_id' => $request->group,
            'status' => $request->p_status,
            'notes' => $request->comments,
        ]);

        return response()->json([
            'message' => __('Staffing_Company/Project_Planning/p_index.update_planning'),
            'status' => true
        ], 200);
    }
    public function destroy($id, Request $request)
    {
        $planning = EmployeeProjectPlanning::findOrFail($id);


        $dayNo = Carbon::parse($planning->date)->dayOfWeekIso;


        $planning->update(['planning_delete' => 1]);


        $weekState = WeekState::where('week_no', $planning->week_no)
            ->whereHas(
                'staffingProjects',
                fn($q) => $q->where('staffing_project_id', $planning->project_id)
            )
            ->first();

        if ($weekState) {


            $weekCard = SfWeekCard::where('week_state_id', $weekState->id)
                ->where('personnel_id', $planning->employee_id)
                ->first();

            if ($weekCard) {


                for ($i = $dayNo; $i <= 7; $i++) {
                    $column = "hours_" . $i;
                    $weekCard->{$column} = 0;
                }

                $weekCard->total_hours = array_sum([
                    $weekCard->hours_1,
                    $weekCard->hours_2,
                    $weekCard->hours_3,
                    $weekCard->hours_4,
                    $weekCard->hours_5,
                    $weekCard->hours_6,
                    $weekCard->hours_7,
                ]);



                $weekCard->save();
            }
        }

        return response()->json([
            'message' => "Employee removed from planning for this date: " . $planning->date,
            'confirm_required' => false
        ]);
    }
    public function employeeExistInCurrentProject($project_id, $employee_id)
    {
        return EmployeeProjectPlanning::where('project_id', $project_id)
            ->where('employee_id', $employee_id)->exists();
    }

    // public function toggleProjectInactive(Request $request, $projectId)
    // {
    //     $newInactiveStatus = $request->input('inactive') == 1 ? 1 : 0;


    //     $selectedDate = Carbon::parse($request->date);
    //     $year   = $selectedDate->format('o');
    //     $week   = str_pad($selectedDate->format('W'), 2, '0', STR_PAD_LEFT);
    //     $weekNo = $year . $week;

    //     $employeePlannings = EmployeeProjectPlanning::where('project_id', $projectId)
    //         ->where('planning_delete', 0)
    //         ->where('date', $selectedDate->format('Y-m-d'))
    //         ->get();

    //     if ($employeePlannings->isEmpty()) {
    //         return redirect()->back()->with('error', 'No active employee planning found for this project on selected date.');
    //     }

    //     foreach ($employeePlannings as $ep) {
    //         $ep->inactive = $newInactiveStatus;
    //         $ep->save();

    //         if (!$newInactiveStatus) continue;

    //         $dayIndex = $selectedDate->dayOfWeekIso; // 1 = Monday, 7 = Sunday
    //         $dayField = "hours_" . $dayIndex;

    //         $weekState = WeekState::where('week_no', $weekNo)
    //             ->whereHas('staffingProjects', function ($q) use ($projectId) {
    //                 $q->where('staffing_project_id', $projectId);
    //             })
    //             ->first();

    //         if (!$weekState) continue;

    //         $weekCard = SfWeekCard::where('week_state_id', $weekState->id)
    //             ->where('personnel_id', $ep->employee_id)
    //             ->where('week_no', $weekNo)
    //             ->first();

    //         if (!$weekCard) continue;

    //         $weekCard->$dayField = 0;

    //         $weekCard->total_hours =
    //             ($weekCard->hours_1 ?? 0) +
    //             ($weekCard->hours_2 ?? 0) +
    //             ($weekCard->hours_3 ?? 0) +
    //             ($weekCard->hours_4 ?? 0) +
    //             ($weekCard->hours_5 ?? 0) +
    //             ($weekCard->hours_6 ?? 0) +
    //             ($weekCard->hours_7 ?? 0);

    //         $weekCard->save();
    //     }

    //     $statusMessage = $newInactiveStatus ? 'Project set as inactive for selected date.' : 'Project set as active.';
    //     return redirect()->back()->with('success', $statusMessage);
    // }
    public function toggleProjectInactive(Request $request, $projectId)
    {
        $newInactiveStatus = $request->input('inactive') == 1 ? 1 : 0;

        $selectedDate = Carbon::parse($request->date);
        $year   = $selectedDate->format('o');
        $week   = str_pad($selectedDate->format('W'), 2, '0', STR_PAD_LEFT);
        $weekNo = $year . $week;

        $employeePlannings = EmployeeProjectPlanning::where('project_id', $projectId)
            ->where('planning_delete', 0)
            ->where('date', $selectedDate->format('Y-m-d'))
            ->get();

        if ($employeePlannings->isEmpty()) {
            return redirect()->back()->with('error', 'No active employee planning found for this project on selected date.');
        }

        foreach ($employeePlannings as $ep) {
            $ep->inactive = $newInactiveStatus;
            $ep->save();

            if (!$newInactiveStatus) continue;

            $dayIndex = $selectedDate->dayOfWeekIso; // 1 = Monday, 7 = Sunday

            $weekState = WeekState::where('week_no', $weekNo)
                ->whereHas('staffingProjects', function ($q) use ($projectId) {
                    $q->where('staffing_project_id', $projectId);
                })
                ->first();

            if (!$weekState) continue;

            $weekCard = SfWeekCard::where('week_state_id', $weekState->id)
                ->where('personnel_id', $ep->employee_id)
                ->where('week_no', $weekNo)
                ->first();

            if (!$weekCard) continue;

            // Loop from inactive day to Friday (5 = Friday)
            for ($d = $dayIndex; $d <= 5; $d++) {
                $field = 'hours_' . $d;
                $weekCard->$field = 0;
            }

            // Recalculate total hours
            $weekCard->total_hours =
                ($weekCard->hours_1 ?? 0) +
                ($weekCard->hours_2 ?? 0) +
                ($weekCard->hours_3 ?? 0) +
                ($weekCard->hours_4 ?? 0) +
                ($weekCard->hours_5 ?? 0) +
                ($weekCard->hours_6 ?? 0) +
                ($weekCard->hours_7 ?? 0);

            $weekCard->save();
        }

        $statusMessage = $newInactiveStatus ? 'Project set as inactive for selected date onwards.' : 'Project set as active.';
        return redirect()->back()->with('success', $statusMessage);
    }
}
