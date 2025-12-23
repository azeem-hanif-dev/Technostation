<?php

namespace App\Http\Controllers\StaffingCompany;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\WeekState;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeFunction;
use App\Models\StaffingCompany\EmployeeProjectPlanning;

class ProjectPlanningController extends Controller
{

    public function index()
    {
        $plannings = ProjectPlanning::latest()->get();
        $projects = StaffingProject::where('active', 1)
            ->orderBy('name')
            ->get();
        $currentWeek = Carbon::now()->weekOfYear;
        $currentYear = Carbon::now()->year;
        $count = ProjectPlanning::where('week_no', $currentYear . $currentWeek)
            ->whereHas('employeeProjects', function ($q) {
                $q->where('planning_delete', 0);
            })
            ->count();
        return view('StaffingCompany.ProjectPlanning.index', compact('plannings', 'projects', 'count', 'currentWeek'));
    }
    public function currentWeekPlanning()
    {
        $currentWeek = Carbon::now()->weekOfYear;
        $currentYear = Carbon::now()->year;
        $plannings = ProjectPlanning::where('week_no', $currentWeek)
            ->whereYear('date', $currentYear)
            ->with(['employeeProjects', 'staffingProjects'])
            ->latest()
            ->get();
        $activeProjectsCount = StaffingProject::whereHas('ProjectPlannings', function ($query) use ($currentWeek, $currentYear) {
            $query->where('week_no', $currentWeek)
                ->whereYear('date', $currentYear);
        })->count();


        $assignedPersonnelIds = EmployeeProjectPlanning::where('week_no', $currentWeek)
            ->whereYear('date', $currentYear)
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        $assignedPersonnelCount = count($assignedPersonnelIds);

        $loggedInCount = 0;
        $notLoggedInCount = 0;

        if (!empty($assignedPersonnelIds)) {
            $loggedInCount = Personnel::whereIn('id', $assignedPersonnelIds)
                ->where('is_logged_in', 1)
                ->count();

            $notLoggedInCount = $assignedPersonnelCount - $loggedInCount;
        }
        return view('StaffingCompany.partials._dashboard', [
            'data' => [
                'activeProjects' => $activeProjectsCount,
                'assignedPersonnel' => $assignedPersonnelCount,
                'loggedInCount' => $loggedInCount,
                'notLoggedInCount' => $notLoggedInCount,
                'currentWeek' => $currentWeek,
            ]
        ]);
    }

    public function create()
    {
        // dd('create');
        $e_functions = EmployeeFunction::orderBy('name')->get();
        $projects = StaffingProject::where('active', 1)
            ->orderBy('name')
            ->get();
        $personnels = Personnel::where('active', 1)->orderBy('first_name')->get();

        return view(
            'StaffingCompany.ProjectPlanning.create',
            compact('projects', 'e_functions', 'personnels')
        );
    }
    public function store(Request $request)
    {
        $date = Carbon::parse($request->planning_date)->format('Y-m-d');
        $carbonDate = Carbon::parse($request->planning_date);
        $weekNo = str_pad($carbonDate->weekOfYear, 2, '0', STR_PAD_LEFT);
        $year   = $carbonDate->year;
        $week_no_full = $year . $weekNo;

        $exists = ProjectPlanning::where('date', $date)
            ->whereHas('staffingProjects', function ($q) use ($request) {
                $q->where('staffing_projects.id', $request->project_id);
            })
            ->exists();

        if ($exists) {
            return back()->with(
                'message',
                "A planning already exists for staffing project {$request->project_id} on {$date}."
            );
        }

        $projectPlanning = ProjectPlanning::firstOrCreate(
            ['date' => $date],
            ['week_no' => $week_no_full]
        );

        $projectPlanning->staffingProjects()->syncWithoutDetaching([$request->project_id]);

        $projectPlanning->load('staffingProjects');

        $this->ensureWeekStatesForPlanning($projectPlanning);

        return $request->planning_project_id
            ? redirect()->route('project_plannings.edit', $request->planning_project_id)
            : redirect()->route('project_plannings.index');
    }

    public function edit(Request $request, $id)
    {

        $project_planning = ProjectPlanning::findOrFail($id);
        $date = $project_planning->date;
        $day = Carbon::parse($date)->format('l');
        $day_no = Carbon::parse($date)->dayOfWeek;
        $week_no = Carbon::parse($date)->weekOfYear;
        $projects = StaffingProject::where('active', true)
            ->orderBy('name')
            ->get();
        $planning_projects = $project_planning->staffingProjects()->latest();
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $planning_projects->where('name', 'LIKE', "%{$search}%");
        }
        $total_projects = $planning_projects->count();

        $planning_projects = $planning_projects->get();
        $employee_plannings = $project_planning->employeeProjects()
            ->select(
                'id',
                'employee_id',
                'project_id',
                'project_planning_id',
                'date',
                'inactive',
                'geschikt',
                'status',
                'notes',
                'group_id',
                'planning_delete'
            )
            ->latest()
            ->get();


        return view('StaffingCompany.ProjectPlanning.index2', compact(
            'project_planning',
            'date',
            'id',
            'planning_projects',
            'employee_plannings',
            'projects',
            'day',
            'day_no',
            'week_no',
            'total_projects'
        ));
    }
    public function destroy(ProjectPlanning $project_planning)
    {
        EmployeeProjectPlanning::where('project_planning_id', $project_planning->id)->delete();
        $project_planning->delete();

        return redirect()->back()->with('message', 'Project Planning Deleted Successfully!');
    }

    public function removeProject($date, $projectId)
    {
        $removeDate = Carbon::parse($date);
        $weekStart  = $removeDate->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd    = $removeDate->copy()->endOfWeek(Carbon::FRIDAY);
        $projectPlanning = ProjectPlanning::where('date', $date)->firstOrFail();
        $employeeCount = $projectPlanning->employeeProjects()
            ->where('project_id', $projectId)
            ->where('planning_delete', 0)
            ->count();
        if ($employeeCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot remove project. First remove all employees from this project.'
            ]);
        }
        $projectPlanning->staffingProjects()->detach($projectId);
        $employees = EmployeeProjectPlanning::where('project_id', $projectId)
            ->where('planning_delete', 1)
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->pluck('employee_id')
            ->unique();
        $year   = $removeDate->year;
        $week   = str_pad($removeDate->weekOfYear, 2, '0', STR_PAD_LEFT);
        $weekNo = $year . $week;


        foreach ($employees as $employeeId) {

            $weekCard = SfWeekCard::where('personnel_id', $employeeId)
                ->where('week_no', $weekNo)
                ->first();
            if (!$weekCard) continue;
            $cursor = $weekStart->copy();
            $dayIndex = 1;
            while ($cursor <= $weekEnd) {
                if ($cursor->gte($removeDate)) {
                    $field = "hours_" . $dayIndex;
                    $weekCard->$field = 0;
                }
                $cursor->addDay();
                $dayIndex++;
            }
            $weekCard->total_hours =
                $weekCard->hours_1 +
                $weekCard->hours_2 +
                $weekCard->hours_3 +
                $weekCard->hours_4 +
                $weekCard->hours_5;
            $weekCard->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Project removed and weekly statement updated correctly'
        ]);
    }

    protected function ensureWeekStatesForPlanning(ProjectPlanning $planning)
    {
        $planningDate = Carbon::parse($planning->date);
        $weekStart = $planningDate->copy()->startOfWeek();
        $weekDays = [];
        for ($i = 0; $i < 5; $i++) {
            $weekDays[] = $weekStart->copy()->addDays($i);
        }

        $projects = $planning->staffingProjects;

        foreach ($projects as $project) {
            foreach ($weekDays as $idx => $day) {
                $dayIndex = $idx + 1;
                $dayPlanning = ProjectPlanning::where('date', $day->format('Y-m-d'))->first();
                if (!$dayPlanning) continue;

                $employees = $dayPlanning->employees ?? [];

                foreach ($employees as $emp) {

                    $empPlan = EmployeeProjectPlanning::firstOrCreate([
                        'employee_id' => $emp->id,
                        'project_id' => $project->id,
                        'project_planning_id' => $dayPlanning->id
                    ], [
                        'status' => 1,
                        'planning_delete' => 0
                    ]);

                    $card = SfWeekCard::firstOrCreate([
                        'week_state_id' => $planning->week_no,
                        'personnel_id'  => $emp->id,
                        'week_no'       => $planning->week_no,
                    ], [
                        'hours_1' => 0,
                        'hours_2' => 0,
                        'hours_3' => 0,
                        'hours_4' => 0,
                        'hours_5' => 0,
                        'hours_6' => 0,
                        'hours_7' => 0,
                        'total_hours' => 0,
                    ]);

                    if ($day < $planningDate) {

                        continue;
                    }

                    if (!$empPlan->planning_delete) {

                        $card->{'hours_' . $dayIndex} = 8;
                    } else {

                        $card->{'hours_' . $dayIndex} = 0;
                    }

                    $card->total_hours = $card->hours_1 + $card->hours_2 + $card->hours_3 + $card->hours_4 + $card->hours_5;
                    $card->save();
                }
            }
        }
    }

    // public function createWeekState(Request $request, $id)
    // {
    //     $planning = ProjectPlanning::with([
    //         'staffingProjects',
    //         'employeeProjects' => fn($q) => $q->where('planning_delete', 0)
    //     ])->findOrFail($id);
    //     $week_no = $planning->week_no;

    //     $year = (int) substr($week_no, 0, 4);
    //     $week = (int) substr($week_no, 4, 2);

    //     foreach ($planning->staffingProjects as $sp) {

    //         $weekState = WeekState::where('week_no', $week_no)
    //             ->whereHas('staffingProjects', fn($q) => $q->where('staffing_project_id', $sp->id))
    //             ->first();

    //         if (!$weekState) {
    //             $weekState = WeekState::create(['week_no' => $week_no]);
    //             $weekState->staffingProjects()->attach($sp->id);
    //         }

    //         $projectEmployees = $planning->employeeProjects->where('project_id', $sp->id);
    //         if ($projectEmployees->isEmpty()) continue;

    //         foreach ($projectEmployees as $empPlan) {

    //             $dayAdded = Carbon::parse($empPlan->date)->dayOfWeekIso;

    //             $existingCard = SfWeekCard::where('week_state_id', $weekState->id)
    //                 ->where('personnel_id', $empPlan->employee_id)
    //                 ->first();

    //             // previous week calc
    //             $prevWeek = $week - 1;
    //             if ($prevWeek == 0) {
    //                 $prevYear = $year - 1;
    //                 $prevWeek = 52;
    //             } else {
    //                 $prevYear = $year;
    //             }

    //             $prevWeekNo = $prevYear . str_pad($prevWeek, 2, '0', STR_PAD_LEFT);

    //             $prevWeekCard = SfWeekCard::where('week_no', $prevWeekNo)
    //                 ->whereHas('weekState.staffingProjects', fn($q) =>
    //                 $q->where('staffing_project_id', $sp->id))
    //                 ->where('personnel_id', $empPlan->employee_id)
    //                 ->first();
    //             $hours = array_fill(1, 7, 0);

    //             if ($existingCard) {
    //                 if ($prevWeekCard) {
    //                     for ($d = 1; $d <= 7; $d++) {
    //                         $prevHour = $prevWeekCard->{'hours_' . $d};

    //                         if ($d < $dayAdded && $existingCard && $existingCard->{'hours_' . $d} == 0) {
    //                             $hours[$d] = 0;
    //                         } else {
    //                             $hours[$d] = $prevHour;
    //                         }
    //                     }
    //                 } else {

    //                     $prevHours = [
    //                         $existingCard->hours_1,
    //                         $existingCard->hours_2,
    //                         $existingCard->hours_3,
    //                         $existingCard->hours_4,
    //                         $existingCard->hours_5,
    //                     ];

    //                     $isAllZero = array_sum($prevHours) == 0;

    //                     if ($isAllZero) {
    //                         for ($d = $dayAdded; $d <= 5; $d++) $hours[$d] = 8;
    //                     } else {
    //                         for ($d = 1; $d <= 7; $d++) $hours[$d] = $existingCard->{'hours_' . $d};
    //                     }
    //                 }

    //                 if ($hours[$dayAdded] == 0) {
    //                     $hours[$dayAdded] = 8;
    //                 }

    //                 $existingCard->update([
    //                     'total_hours' => array_sum($hours),
    //                     'hours_1' => $hours[1],
    //                     'hours_2' => $hours[2],
    //                     'hours_3' => $hours[3],
    //                     'hours_4' => $hours[4],
    //                     'hours_5' => $hours[5],
    //                     'hours_6' => $hours[6],
    //                     'hours_7' => $hours[7],
    //                 ]);

    //                 continue;
    //             }



    //             /* -----------------------------------------------------
    //            NEW WEEK CARD CREATE
    //         -----------------------------------------------------*/


    //             if ($prevWeekCard) {

    //                 $prevMonToFri = [
    //                     $prevWeekCard->hours_1,
    //                     $prevWeekCard->hours_2,
    //                     $prevWeekCard->hours_3,
    //                     $prevWeekCard->hours_4,
    //                     $prevWeekCard->hours_5,
    //                 ];

    //                 $prevAllZero = array_sum($prevMonToFri) == 0;

    //                 if ($prevAllZero) {
    //                     for ($d = 1; $d <= 5; $d++) $hours[$d] = 8;
    //                 } else {
    //                     for ($d = 1; $d <= 7; $d++) $hours[$d] = $prevWeekCard->{'hours_' . $d};
    //                 }
    //             } else {
    //                 for ($d = $dayAdded; $d <= 5; $d++) {
    //                     if ((int)$empPlan->status !== 0) {
    //                         $hours[$d] = 8;
    //                     }
    //                 }
    //             }

    //             if ($hours[$dayAdded] == 0) {
    //                 $hours[$dayAdded] = 8;
    //             }

    //             SfWeekCard::create([
    //                 'week_state_id' => $weekState->id,
    //                 'personnel_id'  => $empPlan->employee_id,
    //                 'week_no'       => $week_no,
    //                 'total_hours'   => array_sum($hours),
    //                 'hours_1' => $hours[1],
    //                 'hours_2' => $hours[2],
    //                 'hours_3' => $hours[3],
    //                 'hours_4' => $hours[4],
    //                 'hours_5' => $hours[5],
    //                 'hours_6' => $hours[6],
    //                 'hours_7' => $hours[7],
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('message', 'Weekly Statement Created Successfully!');
    // }
    // new check add for inactive day hour =0
    public function createWeekState(Request $request, $id)
    {
        $planning = ProjectPlanning::with([
            'staffingProjects',
            'employeeProjects' => fn($q) => $q->where('planning_delete', 0)
        ])->findOrFail($id);

        $week_no = $planning->week_no;
        $year = (int) substr($week_no, 0, 4);
        $week = (int) substr($week_no, 4, 2);

        foreach ($planning->staffingProjects as $sp) {

            $weekState = WeekState::where('week_no', $week_no)
                ->whereHas('staffingProjects', fn($q) => $q->where('staffing_project_id', $sp->id))
                ->first();

            if (!$weekState) {
                $weekState = WeekState::create(['week_no' => $week_no]);
                $weekState->staffingProjects()->attach($sp->id);
            }

            $projectEmployees = $planning->employeeProjects->where('project_id', $sp->id);
            if ($projectEmployees->isEmpty()) continue;

            foreach ($projectEmployees as $empPlan) {

                $dayAdded = Carbon::parse($empPlan->date)->dayOfWeekIso;

                $existingCard = SfWeekCard::where('week_state_id', $weekState->id)
                    ->where('personnel_id', $empPlan->employee_id)
                    ->first();

                // previous week calculation
                $prevWeek = $week - 1;
                $prevYear = $prevWeek == 0 ? $year - 1 : $year;
                $prevWeek = $prevWeek == 0 ? 52 : $prevWeek;
                $prevWeekNo = $prevYear . str_pad($prevWeek, 2, '0', STR_PAD_LEFT);

                $prevWeekCard = SfWeekCard::where('week_no', $prevWeekNo)
                    ->whereHas('weekState.staffingProjects', fn($q) =>
                    $q->where('staffing_project_id', $sp->id))
                    ->where('personnel_id', $empPlan->employee_id)
                    ->first();

                $hours = array_fill(1, 7, 0);

                // ------------------- Existing Card Update -------------------
                if ($existingCard) {
                    if ($prevWeekCard) {
                        for ($d = 1; $d <= 7; $d++) {
                            $prevHour = $prevWeekCard->{'hours_' . $d};

                            // Preserve zero only for the inactive day
                            if ($d == $dayAdded && $prevHour == 0) {
                                $hours[$d] = 0;
                            } else {
                                $hours[$d] = $prevHour == 0 ? 8 : $prevHour;
                            }
                        }
                    } else {
                        for ($d = 1; $d <= 7; $d++) {
                            $hours[$d] = $existingCard->{'hours_' . $d};
                        }
                    }

                    $existingCard->update([
                        'total_hours' => array_sum($hours),
                        'hours_1' => $hours[1],
                        'hours_2' => $hours[2],
                        'hours_3' => $hours[3],
                        'hours_4' => $hours[4],
                        'hours_5' => $hours[5],
                        'hours_6' => $hours[6],
                        'hours_7' => $hours[7],
                    ]);

                    continue;
                }

                // ------------------- New Card Creation -------------------
                if ($prevWeekCard) {
                    for ($d = 1; $d <= 7; $d++) {
                        $prevHour = $prevWeekCard->{'hours_' . $d};

                        // Preserve zero only for the inactive day
                        if ($d == $dayAdded && $prevHour == 0) {
                            $hours[$d] = 0;
                        } else {
                            $hours[$d] = $prevHour == 0 ? 8 : $prevHour;
                        }
                    }
                } else {
                    for ($d = $dayAdded; $d <= 5; $d++) {
                        if ((int)$empPlan->status !== 0) {
                            $hours[$d] = 8;
                        }
                    }
                }
                
                $hours[6] = 0;
                $hours[7] = 0;

                SfWeekCard::create([
                    'week_state_id' => $weekState->id,
                    'personnel_id'  => $empPlan->employee_id,
                    'week_no'       => $week_no,
                    'total_hours'   => array_sum($hours),
                    'hours_1' => $hours[1],
                    'hours_2' => $hours[2],
                    'hours_3' => $hours[3],
                    'hours_4' => $hours[4],
                    'hours_5' => $hours[5],
                    'hours_6' => $hours[6],
                    'hours_7' => $hours[7],
                ]);
            }
        }

        return redirect()->back()->with('message', 'Weekly Statement Created Successfully!');
    }









    private function sumHours($card)
    {
        return $card->hours_1 + $card->hours_2 + $card->hours_3 +
            $card->hours_4 + $card->hours_5 + $card->hours_6 +
            $card->hours_7;
    }

    // public function copyProjectPlanning(Request $request, $id)
    // {
    //     $date = Carbon::parse($request->planning_date)->format('Y-m-d');
    //     $carbonDate = Carbon::parse($request->planning_date);


    //     $weekYear = $carbonDate->format("o");
    //     $weekNum  = $carbonDate->format("W");
    //     $week_no = $weekYear . $weekNum;


    //     $originalPlanning = ProjectPlanning::with(['staffingProjects', 'employeeProjects'])
    //         ->findOrFail($id);


    //     $copiedPlanning = ProjectPlanning::firstOrCreate(
    //         ['date' => $date],
    //         ['week_no' => $week_no]
    //     );


    //     $existingProjectIds = $copiedPlanning->staffingProjects()
    //         ->pluck('staffing_project_id')
    //         ->toArray();

    //     $sourceProjectIds = $originalPlanning->staffingProjects
    //         ->pluck('id')
    //         ->toArray();

    //     $merged = array_unique(array_merge($existingProjectIds, $sourceProjectIds));
    //     $copiedPlanning->staffingProjects()->sync($merged);


    //     foreach ($originalPlanning->employeeProjects->where('planning_delete', 0) as $emp) {

    //         $existing = EmployeeProjectPlanning::where([
    //             'employee_id' => $emp->employee_id,
    //             'project_id'  => $emp->project_id,
    //             'date'        => $date
    //         ])->first();


    //         if ($existing) {
    //             $existing->update([
    //                 'geschikt' => $emp->geschikt,
    //                 'status'   => $emp->status,
    //                 'notes'    => $emp->notes,
    //                 'group_id' => $emp->group_id,
    //                 'week_no'  => $week_no,
    //                 'project_planning_id' => $copiedPlanning->id,
    //             ]);
    //         } else {
    //             EmployeeProjectPlanning::create([
    //                 'week_no' => $week_no,
    //                 'employee_id' => $emp->employee_id,
    //                 'project_id' => $emp->project_id,
    //                 'geschikt' => $emp->geschikt,
    //                 'status'  => $emp->status,
    //                 'group_id' => $emp->group_id,
    //                 'notes' => $emp->notes,
    //                 'project_planning_id' => $copiedPlanning->id,
    //                 'date' => $date,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with(
    //         'message',
    //         'Planning successfully copied for Date: ' . $date
    //     );
    // }
    public function copyProjectPlanning(Request $request, $id)
    {
        $date = Carbon::parse($request->planning_date)->format('Y-m-d');
        $carbonDate = Carbon::parse($request->planning_date);

        $weekYear = $carbonDate->format("o");
        $weekNum  = $carbonDate->format("W");
        $week_no = $weekYear . $weekNum;

        $originalPlanning = ProjectPlanning::with(['staffingProjects', 'employeeProjects'])
            ->findOrFail($id);

        // Create or get the copied planning for the selected date
        $copiedPlanning = ProjectPlanning::firstOrCreate(
            ['date' => $date],
            ['week_no' => $week_no]
        );

        // Filter projects to include only those with at least one active employee
        $projectsWithActiveEmployees = $originalPlanning->staffingProjects->filter(function ($project) use ($originalPlanning) {
            return $originalPlanning->employeeProjects
                ->where('project_id', $project->id)
                ->where('planning_delete', 0)
                ->where('inactive', 0) // only active employees
                ->isNotEmpty();
        });

        // Sync these projects with the copied planning
        $existingProjectIds = $copiedPlanning->staffingProjects()
            ->pluck('staffing_project_id')
            ->toArray();

        $merged = array_unique(array_merge(
            $existingProjectIds,
            $projectsWithActiveEmployees->pluck('id')->toArray()
        ));

        $copiedPlanning->staffingProjects()->sync($merged);

        // Copy only active employees
        foreach (
            $originalPlanning->employeeProjects
                ->where('planning_delete', 0)
                ->where('inactive', 0) as $emp
        ) {

            $existing = EmployeeProjectPlanning::where([
                'employee_id' => $emp->employee_id,
                'project_id'  => $emp->project_id,
                'date'        => $date
            ])->first();

            if ($existing) {
                $existing->update([
                    'geschikt' => $emp->geschikt,
                    'status'   => $emp->status,
                    'notes'    => $emp->notes,
                    'group_id' => $emp->group_id,
                    'week_no'  => $week_no,
                    'project_planning_id' => $copiedPlanning->id,
                ]);
            } else {
                EmployeeProjectPlanning::create([
                    'week_no' => $week_no,
                    'employee_id' => $emp->employee_id,
                    'project_id' => $emp->project_id,
                    'geschikt' => $emp->geschikt,
                    'status'  => $emp->status,
                    'group_id' => $emp->group_id,
                    'notes' => $emp->notes,
                    'project_planning_id' => $copiedPlanning->id,
                    'date' => $date,
                ]);
            }
        }

        return redirect()->back()->with(
            'message',
            'Planning successfully copied for Date: ' . $date
        );
    }


    public function planningPDFF($id)
    {
        $planning = ProjectPlanning::with([
            'employee',
            'staffingProjects',
            'employeeProjectsActive'
        ])->findOrFail($id);

        $function_ids = $planning->employeeProjectsActive->pluck('geschikt')->unique()->toArray();

        $employee_functions = EmployeeFunction::whereIn('id', $function_ids)->get();

        $formatted_functions = $employee_functions->map(function ($e_function) {
            return "{$e_function->name}{{$e_function->code}}";
        })->implode('","', '"');

        $pdf = PDF::loadView('StaffingCompany.ProjectPlanning.pdf', compact('planning', 'formatted_functions'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('planning.pdf');
    }
}
