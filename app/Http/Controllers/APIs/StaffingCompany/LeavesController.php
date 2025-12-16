<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\SCLeave;
use App\Models\StaffingCompany\SCLeaveTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeavesController extends Controller
{

    public function addleave(Request $request)
{
    $rules = [
        'UserId' => ['required'],
        'StartDate' => ['required'],
        'EndDate' => [],
        'Leavetype' => ['required'],
        'Leavedays' => ['required'],
        'Details' => ['required'],
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        $er = $validator->errors();
        return response()->json($er, 201);
    }

    $ValidData = $request->input();

    // Check if leave already exists for the same user and start date
    $existingLeave = SCLeave::where('requested_by', $ValidData['UserId'])
                            ->whereDate('start_date', $ValidData['StartDate'])
                            ->first();

    if ($existingLeave) {
        return response()->json([
            'Message' => 'Leave request already submitted for this date.',
            'Status' => 'Duplicate'
        ], 201);
    }

    $leave = SCLeave::create([
        'details' => $ValidData['Details'],
        'end_date' => $ValidData['EndDate'],
        'leave_type' => $ValidData['Leavetype'] ?? 'No',
        'leave_day_count' => $ValidData['Leavedays'],
        'requested_by' => $ValidData['UserId'],
        'start_date' => $ValidData['StartDate'],
    ]);

    if ($leave) {
        return response()->json([
            'Message' => 'Your Leave request posted',
            'Status'  => 'Success'
        ], 200);
    } else {
        return response()->json([
            'Message' => 'Your Leave request not submitted',
            'Status'  => 'Fail'
        ], 201);
    }
}

    // public function addleave(Request $request)
    // {
    //     $rules = [
    //         'UserId' => ['required'],
    //         'StartDate' => ['required'],
    //         'EndDate' => [],
    //         'Leavetype' => ['required'],
    //         'Leavedays' => ['required'],
    //         'Details' => ['required'],
    //     ];
    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         $er = $validator->errors();
    //         return response()->json($er, 201);
    //     } else {
    //         $ValidData = $request->input();
    //     }
    //     $leave = SCLeave::create([
    //         'details' => $ValidData['Details'],
    //         "end_date" => $ValidData['EndDate'],
    //         "leave_type" => ($ValidData['Leavetype'] ? $ValidData['Leavetype'] : "No"),
    //         "leave_day_count" => $ValidData['Leavedays'],
    //         "requested_by" => $ValidData['UserId'],
    //         'start_date' => $ValidData['StartDate'],
    //     ]);
    //     if ($leave) {
    //         return response()->json([
    //             'Message' => "Your Leave request posted",
    //             'Status'  => "Success"
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'Message' => "Your Leave request not submitted",
    //             'Status'  => "Fail"
    //         ], 201);
    //     }
    // }
    public function all_leave(Request $request)
    {
        $id= $request->UserId;
        $leaves = SCLeave::where("requested_by", $id)->latest()->get();
        if ($leaves) {
            return response()->json($leaves, 200);
        } else {
            return response()->json([
                'Message' => "no Leaves available",
                'Status'  => "Fail"
            ], 201);
        }
    }
    public function LeavesTypes()
    {
        $leaves = SCLeaveTypes::all();
        if ($leaves) {
            return response()->json($leaves, 200);
        } else {
            return response()->json([
                'Message' => "no Leaves available",
                'Status'  => "Fail"
            ], 201);
        }
    }

    public function last_four_week_leave(Request $request){
        $today = Carbon::now()->format('Y-m-d');

        $last4WeekDate = Carbon::now()->subWeeks(4)->format('Y-m-d');
        $leaves = SCLeave::where('requested_by', $request->UserId)->whereBetween('start_date',[$last4WeekDate, $today])->get();
        return response()->json($leaves);


    }
}
