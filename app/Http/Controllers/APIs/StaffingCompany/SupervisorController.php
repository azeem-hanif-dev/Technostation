<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use Carbon\Carbon;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\FileUploadsInWeekstaat;
use App\Models\StaffingCompany\Contact;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\WeekState;
use Illuminate\Support\Facades\Validator;
use App\Models\StaffingCompany\SCTimeCard;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Http\Controllers\StaffingCompany\StaffingProjectController;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    public function getProjectsList($customer_id)
    {
        $projects = StaffingProject::where('customer_id', $customer_id)->where('active', 1)->orderBy('id', 'desc')->get();
        return response()->json([
            'status' => 1,
            'message' => 'Get supervisor projects list',
            'data' => [
                'projects' => $projects
            ]
        ]);
    }

    public function test(Request $request)
    {
        $id = 4467;
        $projects = StaffingProject::select('Id', 'Name', "created_at")->where('Contact_id', '=', $id)->get(); //where('Active', '=', '1')->orderBy('Id', 'desc')->get();
        return response()->json([
            'status' => 1,
            'message' => 'Get supervisor projects list',
            'data' => [
                'projects' => $projects
            ]
        ]);
    }

    public function projectPlanning($id)
    {
        $planings = EmployeeProjectPlanning::with(['projectPlanning', 'personnel', 'staffGroup'])->select('project_id', 'project_planning_id', 'week_no')
            ->distinct()
            ->where('project_id', $id)
            ->get();

        return response()->json([
            'status' => 1,
            'message' => 'Project Planning',
            'data' => [
                'count' => sizeof($planings),
                'planning' => $planings
            ]
        ]);
    }

    public function employees_list($week_no, $project_id)
    {
        // $project_planning = ProjectPlanning::find($id);
        // if (!$project_planning) {
        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'Project Planning not found',
        //     ]);
        // }

        //$week_no = $project_planning->week_no;


        $week_stat = WeekState::where('week_no', $week_no)
            ->whereHas('projects', function ($query) use ($project_id) {
                $query->where('staffing_project_id', $project_id);
            })->first();
        if (!$week_stat) {
            return response()->json([
                'status' => 0,
                'message' => 'Week State not found for the given week number and project',
            ]);
        }
        //$employee_ids = EmployeeProjectPlanning::where('project_planning_id',$id)->where('project_id', $project_id)->pluck('employee_id')->unique()->toArray();
        $employee_ids = EmployeeProjectPlanning::where('week_no', $week_no)->where('project_id', $project_id)->pluck('employee_id')->unique()->toArray();
        $personnels = Personnel::with(['weekCards' => function ($query) use ($week_stat) {
            $query->where('week_state_id', $week_stat->id)->with('timeCards');
        }])->whereIn('id', $employee_ids)->get();

        $approved = true;
        $week_minutes = 0;
        foreach ($personnels as $personnel) {
            $hours_approved = true;
            $total_minutes = 0;

            foreach ($personnel->weekCards as $weekCard) {
                foreach ($weekCard->timeCards as $timeCard) {
                    // Sum total time
                    if ($timeCard->total_time) {
                        $timeParts = explode(':', $timeCard->total_time);
                        $hours = (int)$timeParts[0];
                        $minutes = (int)$timeParts[1];

                        $total_minutes += $hours * 60 + $minutes;
                        $week_minutes += $hours * 60 + $minutes;
                    }

                    if ($timeCard->approved == 0) {
                        $approved = false;
                        $hours_approved = false;
                    }
                }
            }

            if ($total_minutes == 0) {
                $approved = false;
                $hours_approved = false;
            }

            $total_hours = floor($total_minutes / 60);
            $total_minutes %= 60;

            $total_time = $total_hours . ':' . $total_minutes;
            $personnel->total_time = $total_time;
            $personnel->hours_approved = $hours_approved;
        }

        $total_hours = floor($week_minutes / 60);
        $week_minutes %= 60;
        $total_week_hours = $total_hours . ':' . $week_minutes;

        $files = FileUploadsInWeekstaat::where('ProjectId', $project_id)
            ->where('WeekNumber', $week_no)
            ->get();

        foreach ($files as $key => $file) {
            $fileUrl = asset('storage/uploads/fileUploadsInWeekstaat/' . $file->WeekNumber . '/' . $file->FileName);
            $file->file_url = $fileUrl;
            $files[$key] = $file;
        }

        return response()->json([
            'status' => 1,
            'message' => 'Employees list that are working on a particular project and date',
            'data' => [
                'count' => sizeof($personnels),
                // 'week_stat' => $week_stat,
                // 'employee_ids' => $employee_ids,
                'employees' => $personnels,
                'totalWeekHours' => $total_week_hours,
                'approved' => $approved,
                'files' => $files,
            ]
        ]);
    }

    public function checkEmployeeAttendance(Request $request)
    {

        $attendance = SCTimeCard::where('sf_week_card_id', $request->week_state_id)
            ->whereDate('date_in', $request->date)
            ->first();

        if ($attendance) {
            return response()->json([
                'status' => 1,
                'message' => 'Employee attendance',
                'data' => [
                    'attendance' => $attendance
                ]
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'Attendance not marked yet',
                'data' => [
                    'planning' => null
                ]
            ]);
        }
    }


    public function approveEmployeeAttendance(Request $request)
    {
        $time_card = SCTimeCard::where('sf_week_card_id', $request->week_card_id)
            ->where('date_in', $request->date)
            ->first();
        if ($request->approved) {
            $time_card->update([
                'approved' => 1,
            ]);
            // dd($request->approved);
        } else {
            $time_card->update([
                'approved' => 0,
            ]);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Employee attendance record is updated',
            'Approved' =>     $time_card->approved,
        ]);
    }
    public function getSupervisorAttendanceHistory(Request $request)
    {
        $supervisor = _supervisor();

        if (!$supervisor) {
            return response()->json([
                'status' => 0,
                'message' => 'Unauthorized. Please login first.'
            ], 401);
        }

        $supervisorId = $supervisor->id;


        $now = Carbon::now();
        $currentWeekNo = $now->format('o') . str_pad($now->format('W'), 2, '0', STR_PAD_LEFT);
        $previous = $now->copy()->subWeek();
        $previousWeekNo = $previous->format('o') . str_pad($previous->format('W'), 2, '0', STR_PAD_LEFT);


        $weekCards = SfWeekCard::with(['weekState.projects'])
            ->where('time_approve', 1)
            ->where('week_no', $previousWeekNo)
            ->whereHas('weekState.projects', function ($query) use ($supervisorId) {
                $query->where('performer', $supervisorId);
            })
            ->get();

        if ($weekCards->isEmpty()) {
            return response()->json([
                'status' => 1,
                'message' => "No approved attendance records found for week {$previousWeekNo}.",
                'current_week' => $currentWeekNo,
                'previous_week' => $previousWeekNo,
                'data' => []
            ]);
        }

        // ✅ Step 3: Group hours by personnel
        $personnelSummary = [];
        $overallTotalHours = 0;

        foreach ($weekCards as $card) {
            $pid = $card->personnel_id;
            $hours = floatval($card->total_hours);

            if (!isset($personnelSummary[$pid])) {
                $personnelSummary[$pid] = [
                    'personnel_id' => $pid,
                    'total_hours' => 0,
                    'records' => [],
                ];
            }

            $personnelSummary[$pid]['total_hours'] += $hours;
            $personnelSummary[$pid]['records'][] = $card;

            $overallTotalHours += $hours;
        }

        // ✅ Step 4: Format final summary
        $summary = array_values($personnelSummary); // reset keys

        return response()->json([
            'status' => 1,
            'message' => "Supervisor attendance history for week {$previousWeekNo} (approved only)",
            'supervisor_id' => $supervisorId,
            'supervisor_name' => $supervisor->name,
            'current_week' => $currentWeekNo,
            'previous_week' => $previousWeekNo,
            'overall_total_hours' => $overallTotalHours,
            'personnel_summary' => $summary,
            'weeks' => $weekCards,
        ]);
    }


    public function fixHoursBySupervisor(Request $request, $id)
    {
        $time_in = Carbon::createFromFormat('h:i A', $request->time_in)->format('H:i:s');
        $time_out = Carbon::createFromFormat('h:i A', $request->time_out)->format('H:i:s');

        $time_card = SCTimeCard::find($id);
        $week_card = $time_card->weekCard;
        $date = Carbon::parse($time_card->date_in);
        $dayOfWeek = $date->dayOfWeek;
        $day = $dayOfWeek == 0 ? 7 : $dayOfWeek;
        $total_time = $this->calculateTotalHours($time_in, $time_out, $time_card->date_in);

        $time_card->update(['check_in_time' => $time_in, 'check_out_time' => $time_out, 'total_time' => $total_time]);

        list($hours, $minutes) = explode(':', $total_time);

        $total_time = (int) $hours;

        $week_card_column = 'hours_' . $day;
        $week_card_total_time = 0;
        if ($week_card->total_hours) {
            for ($i = 1; $i < 8; $i++) {
                if ($day != $i) {
                    $day = 'hours_' . $i;
                    $week_card_total_time += $week_card->$day;
                } else {
                    $week_card_total_time += $total_time;
                }
            }
        }

        $week_card->update([
            'total_hours' => $week_card_total_time,
            $week_card_column => $total_time
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Employee attendance record updated',
        ]);
    }


    public function employeeWorkHoursApprovedBySupervisor($id)
    {

        DB::table('tbl_pln_emp_attendence')->where('id', '=', $id)
            ->update(['approved_by_supervisor' => 1]);

        return response()->json([
            'status' => 1,
            'message' => 'Employee attendance approved successfully',
        ]);
    }

    public function aprroveWeeklyAttendance(Request $request)
    {
        $rules = [
            "weeknumber" => ['required', 'max:100'],
            "employee_id" =>  ['sometimes'],
            "signature" =>  ['sometimes'],
            "supervisor_id" =>  ['sometimes'],
            "projectId" =>  ['required'],
            "planId" =>  ['sometimes'],
            "File" =>  ['sometimes', 'max:10000', 'mimes:pdf,doc,docx,jpg,jpeg,png,bmp'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {

            if ($request->file('File')) {
                $image = $request->file('File');
                $destinationPath = storage_path('uploads/fileUploadsInWeekstaat/' . $request->weeknumber . '/');

                // Check if the file already exists
                $existingFile = FileUploadsInWeekstaat::where('WeekNumber', $request->weeknumber)
                    ->where('ProjectId', $request->projectId)
                    ->where('PlanningId', $request->planId)
                    ->first();

                if ($existingFile) {
                    // Delete the existing file
                    $file = $destinationPath . $existingFile->FileName;
                    if (File::exists($file)) {
                        File::delete($file);
                    }
                }

                $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();

                try {
                    $image->move($destinationPath, $profileImage);
                    FileUploadsInWeekstaat::updateOrCreate(
                        [
                            'WeekNumber' => $request->weeknumber,
                            'ProjectId' => $request->projectId,
                            'PlanningId' => $request->planId,
                        ],
                        [
                            'FileName' => $profileImage
                        ]
                    );
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Failed to upload the file. Error: ' . $e->getMessage()
                    ]);
                }
            }

            if ($request->signature) {
                FileUploadsInWeekstaat::updateOrCreate(
                    [
                        'WeekNumber' => $request->weeknumber,
                        'ProjectId' => $request->projectId,
                        'PlanningId' => $request->planId,
                    ],
                    [
                        'signature' => $request->signature ?? null,
                        'approved_by_id' => $request->signature ? _user()->id : null,
                    ]
                );
            }

            if ($request->employee_id) {
                $year = substr($request->weeknumber, 0, 4);
                $week = substr($request->weeknumber, 4);
                $week_array = $this->getWeekDates($week, $year);

                for ($i = 0; $i < 5; $i++) {
                    SCTimeCard::where('personnel_id', '=', $request->employee_id)->where('date_in', '=', $week_array[$i]['date'])
                        ->update(['approved' => 1]);
                }

                $approved_message = 'Employee Weekly attendance approved successfully.';
            }

            return response()->json([
                'status' => 1,
                'signature' => $request->signature,
                'message' => 'Employee Weekly attendance approved successfully'
            ]);
        }
    }

    public function approveAllEmployeeAttendance(Request $request)
    {
        // $project_planning = ProjectPlanning::find($request->planId);
        $project = StaffingProject::find($request->project_id);

        // if (!$project_planning) {
        //     return response()->json([
        //         'status' => 0,
        //         'message' => 'Project Planning not found',
        //     ]);
        // }

        $week_no = $request->week_no;
        // $week_state = $project_planning->staffingProjects()
        //     ->where('staffing_project_id', $request->project_id)
        //     ->first()
        //     ->weekStates()
        //     ->where('week_no', $week_no)
        //     ->first();

        $week_state = $project->weekStates()
            ->where('week_no', $week_no)
            ->first();

        if (!$week_state) {
            return response()->json([
                'status' => 0,
                'message' => 'Week state not found',
            ]);
        }

        $week_card_ids = $week_state->weekCards()
            ->where('week_no', $week_no)
            ->pluck('id')
            ->unique()
            ->toArray();

        if (empty($week_card_ids)) {
            return response()->json([
                'status' => 0,
                'message' => 'No week card IDs found',
            ]);
        }

        $time_cards = SCTimeCard::whereIn('sf_week_card_id', $week_card_ids)
            ->where('approved', 0)
            ->get();

        if ($time_cards->isEmpty()) {
            return response()->json([
                'status' => 0,
                'message' => 'No time cards found for approval',
            ]);
        }

        $time_cards->each(function ($time_card) {
            $time_card->update(['approved' => 1]);
        });

        return response()->json([
            'status' => 1,
            'message' => 'All employee attendance approved successfully',
        ]);
    }

    ////////////////////////////////////////// Private functions below /////////////////////////////////////////////////

    private function calculateTotalHours($startTime, $endTime, $date)
    {
        $end_time       = date('H:i', strtotime($endTime));
        $endDateFromat  = $date . ' ' . $end_time;
        $endDateFromat  = Carbon::parse($endDateFromat);

        $startDateFormat = $date . ' ' . $startTime;
        $startDateFormat = Carbon::parse($startDateFormat);

        $total_time_in_min = $endDateFromat->diffInMinutes($startDateFormat);
        $hours = intval($total_time_in_min / 60);
        $minutes = $total_time_in_min - ($hours * 60);

        $total_time = $hours . ':' . $minutes;

        return $total_time;
    }

    private function getWeekDates($week, $year)
    {
        $dto = new \DateTime();
        $dto->setISODate($year, $week);
        $week_dates = [];
        $record = [];

        for ($i = 0; $i < 5; $i++) {
            $date = $dto->format('Y-m-d');
            $day  = $dto->format('l');
            $record['date'] = $date;
            $record['day'] = $day;
            // array_push($week_dates, $date);
            array_push($week_dates, $record);
            $dto->modify('+1 day');
        }

        return $week_dates;
    }
    // public function approveOrReject(Request $request)
    // {
    //     $request->validate([
    //         'approved' => 'required|integer|in:0,1,2',
    //         'personnel_id' => 'required|integer|exists:personnels,id',
    //         'week_state_id' => 'required|integer|exists:week_states,id',
    //         'week_no' => 'required|string|max:6',
    //     ]);
    //     $weekCard = SfWeekCard::where('personnel_id', $request->personnel_id)
    //         ->orWhere('week_state_id', $request->week_state_id)
    //         ->orWhere('week_no', $request->week_no)
    //         ->latest()
    //         ->first();

    //     if (!$weekCard) {
    //         return response()->json(['message' => 'Week card not found.'], 404);
    //     }
    //     $weekCard->time_approve = $request->approved;
    //     $weekCard->save();
    //     switch ($weekCard->time_approve) {
    //         case 1:
    //             $message = 'Week card approved successfully.';
    //             break;
    //         case 2:
    //             $message = 'Week card rejected.';
    //             break;
    //         default:
    //             $message = 'Week card marked as pending.';
    //             break;
    //     }
    //     return response()->json([
    //         'message' => $message,
    //         'data' => [
    //             'week_card_id' => $weekCard->id,
    //             'personnel_id' => $weekCard->personnel_id,
    //             'week_no' => $weekCard->week_no,
    //             'total_hours' => $weekCard->total_hours,
    //             'time_approve' => $weekCard->time_approve,
    //         ]
    //     ]);
    // }
    public function approveOrReject(Request $request)
    {
        $request->validate([
            'approved' => 'required|integer|in:0,1,2',
            'personnel_id' => 'required|integer|exists:personnels,id',
            'week_state_id' => 'required|integer|exists:week_states,id',
            'week_no' => 'required|string|max:6',
        ]);
        $weekCard = SfWeekCard::where('personnel_id', $request->personnel_id)
            ->where('week_state_id', $request->week_state_id)
            ->where('week_no', $request->week_no)
            ->first();

        if (!$weekCard) {
            return response()->json(['message' => 'Week card not found.'], 404);
        }

        $weekCard->time_approve = $request->approved;
        $weekCard->save();

        $messages = [
            0 => 'Week card marked as pending.',
            1 => 'Week card approved successfully.',
            2 => 'Week card rejected.'
        ];

        return response()->json([
            'message' => $messages[$weekCard->time_approve] ?? 'Status updated.',
            'data' => [
                'week_card_id' => $weekCard->id,
                'personnel_id' => $weekCard->personnel_id,
                'week_no' => $weekCard->week_no,
                'total_hours' => $weekCard->total_hours,
                'time_approve' => $weekCard->time_approve,
            ]
        ]);
    }


    public function getApprovedTimeCard(Request $request)
    {
        $request->validate([
            'personnel_id' => 'required|integer|exists:sf_week_cards,personnel_id',
            'week_state_id' => 'required|integer|exists:sf_week_cards,week_state_id',
        ]);

        // ✅ Fetch approved time cards
        $approvedTimeCards = SfWeekCard::query()
            ->where('personnel_id', $request->personnel_id)
            ->where('week_state_id', $request->week_state_id)
            ->where('time_approve', 1)
            ->orderByDesc('id') // latest first
            ->get([
                'id',
                'week_state_id',
                'personnel_id',
                'time_approve',
                'updated_at'
            ]);

        // ✅ Return structured response
        return response()->json([
            'status'  => $approvedTimeCards->isNotEmpty() ? 1 : 0,
            'message' => $approvedTimeCards->isNotEmpty()
                ? 'Approved time cards retrieved successfully.'
                : 'No approved time cards found.',
            'data'    => $approvedTimeCards,
        ]);
    }

    public function dailyProjects(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $supervisor = _supervisor();
        $carbonDate = Carbon::parse($date);

        $weekNo = $carbonDate->format('YW');

        $projectPlanning = ProjectPlanning::with('staffingProjects')
            ->whereHas('staffingProjects', function ($proj) use ($supervisor) {
                $proj->where('performer', $supervisor->id);
            })
            ->get()
            ->values();

        $employeeProjectPlanning = EmployeeProjectPlanning::where('week_no', $weekNo)
            ->whereHas('project', function ($proj) use ($supervisor) {
                $proj->where('performer', $supervisor->id);
            })
            ->with([
                'project.weekStates' => function ($query) use ($weekNo) {
                    $query->where('week_no', $weekNo)
                        ->with('weekCards', 'documents');
                },
            ])
            ->get()
            ->unique('project_id')
            ->values();

        return response()->json([
            'employeeProjectPlanning' => $employeeProjectPlanning,
            // 'supervisor' => $supervisor,
            // 'week_no' => $weekNo, 
        ]);
    }

    // all project against supervisor
    public function getProjectsInfo($id)
    {
        $currentWeek = date('YW');
        $weekStateIds = StaffingProject::findOrFail($id)
            ->weekStates()
            ->pluck('week_states.id');
        $weekCards = SfWeekCard::with('personnel:id,first_name,last_name')->whereIn('week_state_id', $weekStateIds)
            ->where('week_no', $currentWeek)
            ->get();
        $totalPersonnel = $weekCards->pluck('personnel_id')->unique()->count();
        $totalHours = $weekCards->sum('total_hours');
        $totalHoursApproved = $weekCards->where('time_approve', 1)->sum('total_hours');
        $totalHoursUnapproved = $weekCards->where('time_approve', 0)->sum('total_hours');
        return response()->json([
            'week_cards' => $weekCards,
            'total_personnel' => $totalPersonnel,
            'total_hours' => $totalHours,
            'total_hours_approved' => $totalHoursApproved,
            'total_hours_unapproved' => $totalHoursUnapproved,
        ]);
    }
}
