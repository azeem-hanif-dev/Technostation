<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\EmployeeProjectAttendance;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\SCTimeCard;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\WeekState;
use App\Models\TimeCard;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{

    public function getEmployeeProjects(Request $request, $id)
    {
        try {
            // Validate input
            if (empty($id) || !is_numeric($id)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid or missing project ID.',
                ], 400);
            }

            $currentWeek = date('YW');

            // Fetch employee planning with related models
            $GetUserPlannings = EmployeeProjectPlanning::with([
                'projectPlanning.staffingProjects',
                'projectPlanning.staffingProjects.projectPerformer:id,first_name,last_name',
                'personnel:id,first_name,last_name'
            ])
                ->where('project_id', $id)
                ->where('week_no', $currentWeek)
                ->orderBy('employee_id', 'ASC')
                ->get()
                ->values();

            // Count total employee plannings for the project
            $count = EmployeeProjectPlanning::where('project_id', $id)
                ->where('week_no', $currentWeek)->count();

            // Handle case where no data found
            if ($GetUserPlannings->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No employee projects found for the given project and week.',
                    'count'   => 0,
                    'data'    => [],
                ], 404);
            }

            // Successful response
            return response()->json([
                'status'  => true,
                'message' => 'Employee projects fetched successfully.',
                'count'   => $count,
                'data'    => $GetUserPlannings,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Project not found.',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Database query error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An unexpected error occurred.',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }
    public function updateHours(Request $request)
    {
        $employeeId = $request->personnel_id;
        $currentWeek = date('YW');
        if ($request->personnel_update_logs) {
            foreach ($request->personnel_update_logs as $personnel) {
                if (!empty($personnel['week_card_id'])) {
                    SfWeekCard::where('week_state_id', $personnel['week_card_id'])
                        ->where('personnel_id', $employeeId)
                        ->where('week_no', $currentWeek)
                        ->update([
                            'hours_1'     => $personnel['hours_1'],
                            'hours_2'     => $personnel['hours_2'],
                            'hours_3'     => $personnel['hours_3'],
                            'hours_4'     => $personnel['hours_4'],
                            'hours_5'     => $personnel['hours_5'],
                            'hours_6'     => $personnel['hours_6'],
                            'hours_7'     => $personnel['hours_7'],
                            'total_hours' => $personnel['total_hours'],
                        ]);
                }
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Hours updated for current week.',
            ]);
        }

        return response()->json([
            'status'  => 0,
            'message' => 'No hours data provided.',
        ]);
    }


    public function Document_upload(Request $request, $id)
    {

        $request->validate([
            'document_logs' => 'required|array',
            'document_logs.*.doc_type' => 'nullable|string',
            'document_logs.*.expiry' => 'nullable|date',
            'document_logs.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $week_state = WeekState::find($id);

        if (!$week_state) {
            return response()->json([
                'status' => 0,
                'message' => 'Week State not found',
            ], 404);
        }

        foreach ($request->document_logs as $document) {
            $file = $document['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store file in storage/app/public/week_state_docs
            $file->storeAs('week_state_docs', $fileName, 'public');

            // Save document record
            $week_state->documents()->create([
                'type' => $document['doc_type'] ?? null,
                'expiry_date' => $document['expiry'] ?? null,
                'file' => $fileName,
            ]);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Documents uploaded successfully',
        ], 200);
    }
    // public function last_four_week_Planning_History(Request $request)
    // {
    //     $employeeId = (int) $request->input('personnel_id');
    //     $currentWeek = (int) $request->input('week_no');

    //     $year = (int) substr($currentWeek, 0, 4);
    //     $week = (int) substr($currentWeek, 4, 2);
    //     $weekNumbers = collect();
    //     for ($i = 0; $i < 4; $i++) {
    //         $targetWeek = $week - $i;
    //         if ($targetWeek > 0) {
    //             $formattedWeek = $year . str_pad($targetWeek, 2, '0', STR_PAD_LEFT);
    //             $weekNumbers->push($formattedWeek);
    //         }
    //     }

    //     $existingWeeks = DB::table('employee_project_plannings')
    //         ->where('employee_id', $employeeId)
    //         ->whereIn('week_no', $weekNumbers->toArray())
    //         ->orderByDesc('week_no')
    //         ->pluck('week_no')
    //         ->unique()
    //         ->values();
    //     $missingWeeks = $weekNumbers->diff($existingWeeks)->values();
    //     if ($existingWeeks->isEmpty()) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => 'No planning data found for the last 4 weeks before the given week.',
    //             'missing_weeks' => $missingWeeks,
    //             'data' => []
    //         ]);
    //     }
    //     $GetUserPlannings = EmployeeProjectPlanning::with([
    //         'projectPlanning.staffingProjects',
    //         'projectPlanning.staffingProjects.projectPerformer:id,first_name,last_name',
    //         'personnel'
    //     ])
    //         ->where('employee_id', $employeeId)
    //         ->whereIn('week_no', $existingWeeks)
    //         ->get()
    //         ->values();
    //     $weekCards = collect();
    //     $warnings = [];

    //     foreach ($GetUserPlannings as $planning) {
    //         $projectId = $planning->project_id;
    //         $weekNo = $planning->week_no;

    //         $weekStateIdz = DB::table('week_state_staffing_project')
    //             ->where('staffing_project_id', $projectId)
    //             ->pluck('week_state_id');

    //         if ($weekStateIdz->isEmpty()) {
    //             $warnings[] = "No Week State IDs found for project_id: {$projectId}";
    //             continue;
    //         }

    //         $sfWeekCard = SfWeekCard::whereIn('week_state_id', $weekStateIdz)
    //             ->where('personnel_id', $employeeId)
    //             ->where('week_no', $weekNo)
    //             ->get();

    //         if ($sfWeekCard->isEmpty()) {
    //             $warnings[] = "No Week Cards found for week_state_ids: " . json_encode($weekStateIdz) . " and personnel_id: {$employeeId} and week_no: {$weekNo}";
    //             continue;
    //         }

    //         $weekCards = $weekCards->merge($sfWeekCard);
    //     }
    //     try {
    //         $id = Auth::user()->id;
    //         $user = User::find($id);
    //         $user->loggedIn = true;
    //         $user->lastLoggedIn = Carbon::now()->setTimezone('Europe/Amsterdam');
    //         $user->save();
    //     } catch (\Exception $e) {
    //         // pass
    //     }

    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Planning history fetched successfully.',
    //         'data' => [
    //             'lastFourWeeks' => $existingWeeks,
    //             'count' => sizeof($GetUserPlannings),
    //             'records' => $GetUserPlannings,
    //             'week_card' => $weekCards,
    //             'warnings' => $warnings,
    //             'No planning History found of this week' => $missingWeeks
    //         ]
    //     ]);
    // }
    public function getEmployeeHistory($id)
    {
        $currentWeek = date('YW');
        $year = (int) substr($currentWeek, 0, 4);
        $week = (int) substr($currentWeek, 4, 2);
        $prevWeeks = [];

        for ($i = 1; $i <= 4; $i++) {
            $week--;
            if ($week < 1) {
                $year--;
                $week = (int) date("W", strtotime($year . "-12-28"));
            }
            $prevWeeks[] = $year . str_pad($week, 2, '0', STR_PAD_LEFT);
        }
        $weekCards = SfWeekCard::with('weekState.projects')
            ->where('personnel_id', $id)
            ->whereIn('week_no', $prevWeeks)
            ->get()->map(function ($weekCard) {
                return [
                    'id' => $weekCard->id,
                    'week_no' => $weekCard->week_no,
                    'personnel_id' => $weekCard->personnel_id,
                    'hours_1' => $weekCard->hours_1,
                    'hours_2' => $weekCard->hours_2,
                    'hours_3' => $weekCard->hours_3,
                    'hours_4' => $weekCard->hours_4,
                    'hours_5' => $weekCard->hours_5,
                    'hours_6' => $weekCard->hours_6,
                    'hours_7' => $weekCard->hours_7,
                    'total_hours' => $weekCard->total_hours,
                    'project_names' => $weekCard->weekState
                        ? $weekCard->weekState->projects->pluck('name')->toArray()
                        : []
                ];
            })
            ->groupBy('week_no');
        return response()->json($weekCards);
    }

    public function getEmployeePlanningHistory(Request $request)
    {

        $currentWeek = date('YW');

        $GetUserPlannings = EmployeeProjectPlanning::with(['projectPlanning.staffingProjects', 'projectPlanning.staffingProjects.projectPerformer:id,first_name,last_name', 'personnel'])
            ->where('employee_id', $request->personnel_id)
            ->where('week_no', $currentWeek)
            ->get();
        $employeeId = $request->personnel_id;
        $weekCards = collect();
        $warnings = [];

        foreach ($GetUserPlannings as $planning) {
            $projectId = $planning->project_id;

            // Find the corresponding week state ID for the project
            $weekStateIdz = DB::table('week_state_staffing_project')
                ->where('staffing_project_id', $projectId)
                ->pluck('week_state_id');


            if ($weekStateIdz->isEmpty()) {
                $warnings[] = "No Week State IDs found for project_id: {$projectId}";
                continue;
            }

            // Retrieve the sf_week_cards data for the given employee and week state ID
            $sfWeekCards = SfWeekCard::with('timeCards')
                ->whereIn('week_state_id', $weekStateIdz)
                ->where('personnel_id', $employeeId)
                ->where('week_no', $currentWeek)
                ->get();

            if ($sfWeekCards->isEmpty()) {
                $warnings[] = "No Week Cards found for week_state_ids: " . json_encode($weekStateIdz) . " and personnel_id: {$employeeId} and week_no: {$currentWeek}";
                continue;
            }
            // Add project_id to each week card record
            $sfWeekCards = $sfWeekCards->map(function ($card) use ($projectId) {
                $card->setAttribute('project_id', $projectId); // attach project_id temporarily
                return $card;
            });

            $weekCards = $weekCards->merge($sfWeekCards);
        }

        //online status
        try {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->loggedIn      = true;
            $user->lastLoggedIn  = Carbon::now()->setTimezone('Europe/Amsterdam');
            $user->save();
        } catch (\Exception $e) {
            //pass
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'currentWeek' => $currentWeek,
                'count' => sizeof($GetUserPlannings),
                'records' => $GetUserPlannings,
                'week_card' => $weekCards,
                'warnings' => $warnings
            ]
        ]);
    }
    public function getEmployeePlanning(Request $request)
    {
        $project_id = $request->project_id;
        $emp_id = $request->emp_id;
        $date_in = date('Y-m-d', strtotime($request->plan_date));
        $employee_project_planning = EmployeeProjectPlanning::where('employee_id', $emp_id)
            ->where('project_id', $project_id)->first();

        if ($employee_project_planning) {
            $planning = $employee_project_planning->projectPlanning;
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Employee project planning not exits'
            ]);
        }

        $project_details = StaffingProject::find($project_id);

        if ($planning) {
            $week_state = $project_details->weekStates()->where('week_no', $employee_project_planning->week_no)->first();
            if ($week_state) {
                $week_card = $week_state->weekCards()->where('week_no', $week_state->week_no)->where('personnel_id', $emp_id)->first();
                $time_cards = $week_card ? $week_card->timeCards()->whereDate('date_in', '<=', Carbon::now()->format('Y-m-d'))->get() : null;
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Project week state not found against week no#' . $employee_project_planning->week_no,
                ]);
            }

            $attendance_in = SCTimeCard::where('personnel_id', $request->emp_id)->whereDate('date_in', $date_in)->first();
            $attendance_out = SCTimeCard::where('personnel_id', $request->emp_id)->whereDate('date_out', $date_in)->first();
            $check_in = (bool)$attendance_in;
            $check_out = (bool)$attendance_out;
            $check_in_time = $attendance_in ? $attendance_in->check_in_time : null;
            $check_out_time = $attendance_out ? $attendance_out->check_out_time : null;
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Project Planning not exits'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'project_details' => $project_details->load(['customer', 'projectPlannings', 'department', 'weekStates']),
                'time_card' => $time_cards,
                'did_check_in' => $check_in,
                'did_check_out' => $check_out,
                'check_in_time' => $check_in_time,
                'check_out_time' => $check_out_time,
            ]
        ]);
    }
    public function employeeCheckIn(Request $request)
    {
        $date_in = date("Y-m-d", strtotime($request->date_in));

        $week_state = WeekState::find($request->week_state_id);
        $alreadyCheckedIn = SCTimeCard::where('personnel_id', $request->personnel_id)
            ->where('date_in', $date_in)
            ->whereHas('weekCard', function ($q) use ($request) {
                $q->where('week_state_id', $request->week_state_id);
            })
            ->exists();

        if ($alreadyCheckedIn) {
            return response()->json([
                'status' => 0,
                'message' => 'Already checked in for this project today.',
            ]);
        }

        $week_card = SfWeekCard::updateOrCreate(
            [
                'week_state_id' => $request->week_state_id,
                'personnel_id' => $request->personnel_id,
            ],
            [
                'personnel_id' => $request->personnel_id,
                'week_state_id' => $request->week_state_id,
                'week_no' => $week_state->week_no,
                'hours_1' => 0.0,
                'hours_2' => 0.0,
                'hours_3' => 0.0,
                'hours_4' => 0.0,
                'hours_5' => 0.0,
                'hours_6' => 0.0,
                'hours_7' => 0.0,
                'total_hours' => 0.0,
                'customer' => 0,
                'cost' => 0,
                'directing' => 0,
                'comments' => 'nothing',
            ]
        );

        $timeCard = $week_card->timeCards()->create([
            'personnel_id' => $request->personnel_id,
            'day_name' => Carbon::parse($request->date_in)->format('D'),
            'check_in_time' => $request->time_in,
            'check_in_location' => $request->check_in_location,
            'date_in' => $date_in,
        ]);

        try {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->loggedIn = true;
            $user->lastLoggedIn = Carbon::now()->setTimezone('Europe/Amsterdam');
            $user->save();
        } catch (\Exception $e) {
            // Log or ignore
        }

        return response()->json([
            'status' => 1,
            'message' => 'Checked in successfully',
            'data' => $timeCard->id
        ]);
    }
    public function employeeCheckOut(Request $request)
    {
        $date_out = date("Y-m-d", strtotime($request->date_out));

        $endDateFromat = Carbon::parse($request->date_out . ' ' . $request->time_out);

        $personnel_id = $request->personnel_id;

        $time_card = SCTimeCard::where('date_in', $date_out)
            ->where('personnel_id',  $personnel_id)->where('id', $request->time_card_id)
            ->first();
        if (!$time_card) {
            return response()->json([
                'status' => 0,
                'message' => 'Sorry! Time Card Not found!'
            ]);
        }

        $week_card = $time_card->weekCard;

        $startDateFormat = Carbon::parse($request->date_out . ' ' . $time_card->check_in_time);

        $total_time_in_min = $endDateFromat->diffInMinutes($startDateFormat);

        $hours = floor($total_time_in_min / 60);
        $minutes = $total_time_in_min % 60;
        $total_time_decimal = $total_time_in_hours_rounded_half = round(round(($total_time_in_min / 60), 1) * 2) / 2;

        $time_card->update([
            'check_out_time' => $request->time_out,
            'check_out_location' => $request->check_out_location,
            'date_out' => $date_out,
            'total_time' => $hours . ':' . $minutes,
        ]);

        $dayOfWeek = Carbon::parse($request->date_out)->dayOfWeek;

        $columnName = 'hours_' . ($dayOfWeek == 0 ? 7 : $dayOfWeek);

        $week_card->update([
            $columnName => $total_time_decimal,
        ]);

        $totalTimeForWeek = 0;
        for ($i = 1; $i <= 7; $i++) {
            $columnName = 'hours_' . $i;
            $totalTimeForWeek += $week_card->$columnName;
        }

        $week_card->update([
            'total_hours' => $totalTimeForWeek
        ]);

        return response()->json([
            'status' => 1,
            '$week_card' => $week_card,
            'message' => 'Checked out successfully'
        ]);
    }

    //////////////////////////////////// PRIVATE FUNCTIONS BELOW ////////////////////

    private function getEmployeeWeeklyWorkingHours($emp_id, $date)
    {
        return $weekNumber = $this->getWeekNumber($date);
        $week = substr($weekNumber, 4, 2);
        $year = substr($weekNumber, 0, 4);
        $week_dates = $this->getWeekDates($week, $year);
        $total_hours = $this->calculateWorkingHours($emp_id, $week_dates);
        return $total_hours;
    }

    private function getWeekNumber($comingDate)
    {
        return date("YW", strtotime($comingDate));
    }

    private function getWeekDates($week, $year)
    {
        $dates = [];
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        for ($i = 0; $i < 5; $i++) {
            $date = $dto->format('Y-m-d');
            array_push($dates, $date);
            $dto->modify('+1 days');
        }
        return $dates;
    }

    private function calculateWorkingHours($emp_id, $week_dates)
    {
        $sum_hours = 0;
        for ($i = 0; $i < 5; $i++) {
            $hours = DB::table('tbl_pln_emp_attendence')->select('total_time')
                ->where('employee_id', '=', $emp_id)
                ->where('date_in', '=', $week_dates[$i])
                ->first();

            if ($hours) {
                $hours = str_replace(':', '.', $hours->total_time);;
                $sum_hours += floatval($hours);
            }
        }

        return $sum_hours;
    }

    public function SendEmail(Request $request)
    {
        //        Config::set('mail.driver', env('CONTACT_DRIVER'));
        //        Config::set('mail.host', env('CONTACT_HOST'));
        //        Config::set('mail.port', env('CONTACT_PORT'));
        //        Config::set('mail.encryption', env('CONTACT_ENCRYPTION'));
        //        Config::set('mail.username', env('CONTACT_MAIL'));
        //        Config::set('mail.password', env('CONTACT_PASSWORD'));
        $rules = [
            'Email' => ['required', 'email', 'unique:users'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {
            $ValidData = $request->input();
        }
        $Code = rand(0, 9999) + 1000;
        DB::table('password_resets')
            ->where('email', $ValidData['Email'])
            ->update(['status' => 2]);
        $email = DB::table('password_resets')->insert([
            'email' => $ValidData['Email'],
            'token' => $Code,
            'created_at' => Carbon::now(),
        ]);
        if ($email) {
            $emails = $ValidData['Email'];
            Mail::raw($Code, function ($message) use ($emails) {
                $message->to($emails)
                    ->subject('Update Email');
                $message->from('planning@easycleanup.nl', 'Planing');
            });
            return response()->json([
                'Message' => "Verification Send to your Email",
                'Status' => "Success"
            ], 200);
        } else {
            return response()->json([
                'Message' => "Your Request can't proceed Please try again later",
                'Status' => "Fail"
            ], 201);
        }
    }

    public function UpdateEmail(Request $request)
    {
        $rules = [
            'Email' => ['required', 'email', 'unique:users'],
            "CODE" => ['required'],
            "UserId" => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {
            $ValidData = $request->input();
        }

        $email = DB::table('password_resets')->where("email", $ValidData['Email'])->where("status", 0)->orderBy('id', 'DESC')->first();
        if ($email) {
            //            return  $ValidData['CODE'];
            if ($email->token == $ValidData['CODE']) {
                $affected = DB::table('password_resets')
                    ->where('id', $email->id)
                    ->update(['status' => 1]);
                $User = User::find($ValidData['UserId']);
                if ($User) {
                    $User->email = $ValidData['Email'];
                    $User->save();
                    return response()->json([
                        'Message' => "Your Email Has been Updated",
                        'Status' => "Success"
                    ], 200);
                } else {
                    return response()->json([
                        'Message' => "User not Exist",
                        'Status' => "Fail"
                    ], 201);
                }
            } else {
                return response()->json([
                    'Message' => "Code Not Matched Please Try Again",
                    'Status' => "Fail"
                ], 201);
            }
        } else {
            return response()->json([
                'Message' => "Request not Found to change Email",
                'Status' => "Fail"
            ], 201);
        }
    }

    public function ChangePassword(Request $request)
    {
        $rules = [
            'Password' => ['required', 'min:6'],
            'CurrentPass' => ['required'],
            "UserID" => ['required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {
            $ValidData = $request->input();
        }
        $User = User::find($ValidData['UserID']);
        // return $User;
        if ($User) {
            if (Hash::check($ValidData['CurrentPass'], $User->password)) {
                $User->password = bcrypt($ValidData['Password']);
                $User->save();
                return response()->json([
                    'Message' => "Your Password has been Changed",
                    'Status' => "Success"
                ], 200);
            } else {
                return response()->json([
                    'Message' => "Password Do not Matched to Old Password",
                    'Status' => "Fail"
                ], 201);
            }
        } else {
            return response()->json([
                'Message' => "User no exist",
                'Status' => "Fail"
            ], 201);
        }
    }

    public function ChangeName(Request $request)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            "UserID" => ['required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {
            $ValidData = $request->input();
        }
        $User = User::find($ValidData['UserID']);
        if ($User) {
            $Personnel = Personnel::where('user_id', $ValidData['UserID'])->first();
            if ($Personnel) {
                $nameParts = explode(' ', $ValidData['name']);
                $Personnel->first_name = array_shift($nameParts);
                $Personnel->last_name = implode(' ', $nameParts);
                $Personnel->save();
            }
            $User->name = $ValidData['name'];
            $User->save();

            $User = User::select("name")->find($ValidData['UserID']);
            return response()->json([
                'Message' => "Your Name has been Changed",
                'Status' => "Success",
                'data' => $User
            ], 200);
        } else {
            return response()->json([
                'Message' => "User not exist",
                'Status' => "Fail",
            ], 201);
        }
    }

    function EmployeeWorkHistory(Request $request)
    {
        $employee = Personnel::find($request->id);

        $data = [];
        if ($employee) {
            $records = $employee->load(['weekCards.timeCards', 'weekCards.weekState', 'planning.projectPlanning.staffingProjects']);
            return response()->json($records, 200);
        } else {
            return response()->json([
                'Message' => "User not exist",
                'Status' => "Fail",
                'data' => $data
            ], 201);
        }
    }
    public function GetProjectManager(Request  $request)
    {
        //return response()->json($request->input());
        // ProjectId
        //$project = Projects::find($request->ProjectId);
        $project = DB::table('tblproject')
            ->Join('tblprojectmanager', 'tblproject.Projectmanager_Id', '=', 'tblprojectmanager.id')
            ->where('tblproject.id', $request->ProjectId)
            ->get();
        return response()->json($project);
    }

    public function getWeekStates()
    {
        $week_states = WeekState::all();

        return response()->json([
            'status' => 200,
            'week_states' => $week_states,
        ]);
    }

    // employee video line method
    public function video_link()
    {
        $videoFolder = public_path('instructional_video');


        if (!File::exists($videoFolder)) {
            File::makeDirectory($videoFolder, 0755, true);
        }

        $files = File::files($videoFolder);

        if (empty($files)) {
            return response()->json([
                'status' => false,
                'message' => 'No instructional videos found.'
            ]);
        }

        $videoUrls = collect($files)->map(function ($file) {
            return asset('instructional_video/' . $file->getFilename());
        });

        return response()->json([
            'status' => true,
            'videos' => $videoUrls
        ]);
    }
}
