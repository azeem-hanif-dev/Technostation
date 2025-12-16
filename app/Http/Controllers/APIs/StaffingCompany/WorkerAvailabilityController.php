<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\WorkerAvailability;
use App\Models\StaffingCompany\EmployeeProjectPlanning;

class WorkerAvailabilityController extends Controller
{

    public function index($id)
    {
        $today = Carbon::today()->format('Y-m-d');
        $workerAvailability = WorkerAvailability::where('employee_id', $id)
            ->whereDate('created_at', $today)->get();

        if (!$workerAvailability) {
            return response()->json([
                'status' => false,
                'message' => 'Worker availability not found',
            ], 404);
        }

        $workerAvailability->transform(function ($item) {
            $raw = $item->getAttributes()['response'] ?? null;
            $item->response = $raw;
            return $item;
        });

        return response()->json([
            'status' => true,
            'data' => $workerAvailability,
        ], 200);
    }

    public function respond(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:personnels,id',
            'response'    => 'required|string',
        ]);
        $today = Carbon::today()->format('Y-m-d');
        $responseData = [
            'question' => 'Are you available?',
            'answer'   => $request->response,
        ];
        WorkerAvailability::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $today,
            ],
            [
                'response' => $responseData,
            ]
        );
        return response()->json([
            'message' => 'Response saved successfully',
            'data'    => $responseData,
            'status'  => true,
        ], 200);
    }


    // for supervisor

    public function getAvailabilityByPerformer($performerId)
    {

        $projects = StaffingProject::where('performer', $performerId)
            ->select('id', 'name')
            ->get();

        if ($projects->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No projects found for this performer'
            ], 404);
        }

        $projectIds = $projects->pluck('id');

        $projectPlannings = EmployeeProjectPlanning::whereIn('project_id', $projectIds)
            ->with('personnel:id,first_name,last_name')
            ->get();

        if ($projectPlannings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No personnels found for this performer'
            ], 404);
        }


        $personnelIds = $projectPlannings->pluck('personnel.id')->unique();


        $availability = WorkerAvailability::whereIn('employee_id', $personnelIds)
            ->get()
            ->keyBy('employee_id')
            ->transform(function ($item) {
                $item->response = $item->response ?? null;
                return $item;
            });


        $projectsWithPersonnels = $projects->map(function ($project) use ($projectPlannings, $availability) {

            $personnels = $projectPlannings
                ->where('project_id', $project->id)
                ->pluck('personnel')
                ->unique('id')
                ->map(function ($personnel) use ($availability) {
                    $personnel->availability = $availability[$personnel->id] ?? null;
                    return $personnel;
                })
                ->values();

            $project->personnels = $personnels;
            return $project;
        });

        return response()->json([
            'status' => true,
            'performer_id' => $performerId,
            'projects' => $projectsWithPersonnels
        ], 200);
    }
}
