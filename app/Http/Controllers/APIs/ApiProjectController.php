<?php

namespace App\Http\Controllers\APIs;
use DB;
use Auth;
use App\Models\Day;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;

class ApiProjectController extends Controller
{
    public function projectsList(Request $request)
    {
    //different project lists for different roles user(worker),manager, supervisor
      $header = $request->header('language');
      $user_id = Auth::user()->id;
      $role = strtolower(Auth::user()->role->name);
      if ($role == 'user') {
          $project_ids = Day::where('user_id','=',$user_id)->pluck('project_id');
          $projectsList = ProjectResource::collection(Project::whereIn('id',$project_ids)->get());
      }else if ($role == 'supervisor') {
        if(Auth::user()->isInspector()){
          $projectsList = ProjectResource::collection(Project::where('inspector_id','=',$user_id)->get());
        }else {
          $projectsList = ProjectResource::collection(Project::where('supervisor_id','=',$user_id)->get());
        }

      }else if ($role == 'manager') {
          $supervisor_ids = User::where('reports_to_id','=',$user_id)->pluck('id');
          $projectsList =ProjectResource::collection(Project::whereIn('supervisor_id',$supervisor_ids)->get());
      }else {
          $projectsList = false;
      }

      if ($projectsList) {
        $message = ($header == 'en') ? 'Login Worker Project Found.' : 'Login Worker Project gevonden.' ;
        }else {
        $message = ($header == 'en') ? 'No Login Worker Project Found.' : 'Geen Login Worker-project gevonden.' ;
        }

      return response()->json([
          'role' => $role,
          'status' => ($projectsList) ? true : false,
          'message' => $message,
          'projectsList' => $projectsList,
      ]);
    }

    public function myTodayTasks(Request $request,$project_id,$location_id)
    {
      $header = $request->header('language');

      $user_id = Auth::user()->id;
      $day_name = strtolower(date('D')); // mon, tue, wed

      $allJobs = Day::where('user_id','=',$user_id)
                    ->where('project_id','=',$project_id)
                    ->where('location_id','=',$location_id)
                    ->where($day_name,'=',1)
                    ->pluck('id')->toArray();

      $year = date('Y');
      $week = date('W');
      $day = date('D');

      $completedJobs = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'days.id', '=', 'week_cards.days_id')
            ->join('time_cards', 'week_cards.id', '=', 'time_cards.week_cards_id')
            ->where('days.user_id','=',$user_id)
            ->where('days.project_id','=',$project_id)
            ->where("days.".$day_name,'=',1)
            ->where('week_cards.weeknumber','=',$year.$week)
            ->where('time_cards.day_name','=',$day)
            ->where('time_cards.job_status_id','=',3)
            ->whereNotNull('time_cards.total_time')
            ->select('days.id')
            ->get();

// ->select('tasks.id as task_id','tasks.name as task_name','days.*')
       $completedJobs = Arr::pluck($completedJobs, 'id');

       $result = array_diff($allJobs,$completedJobs);
       // $tasks = Day::whereIn('id',$result)->get();
       $tasks =  DB::table('days')
       ->join('tasks', 'tasks.id', '=', 'days.task_id')
       ->join('projects', 'projects.id', '=', 'days.project_id')
       ->join('areas', 'areas.id', '=', 'days.area_id')
       ->join('project_jobs', 'project_jobs.id', '=', 'days.job_id')
       ->join('floors', 'floors.id', '=', 'project_jobs.floor_id')
       ->whereIn('days.id',$result)
       ->select('tasks.name as task_name','projects.name as project_name','areas.name as area_name','floors.name as floor_name','days.*')
       ->get();

         if (!$tasks->isEmpty()) {
           $message = ($header == 'en') ? 'Login Worker Today Tasks Found.' : 'Login Worker Vandaag Taken gevonden.' ;
          }else {
           $message = ($header == 'en') ? 'No Login Worker Today Tasks Found.' : 'Geen login-medewerker vandaag Taken gevonden.' ;
          }

      return response()->json([
          'status' => ($tasks) ? true : false,
          'message' => $message,
          'tasks' => $tasks,
      ]);
    }

    public function projectDetail($id)
    {
      return response()->json([
          'projectDetail' => new ProjectResource(Project::find($id)),
      ]);
    }

    public function projectWorkers(Request $request, $id, $location_id)
    {
      $header = $request->header('language');
      $worker_ids = Day::where('project_id','=',$id)
                ->where('location_id','=',$location_id)
                ->pluck('user_id');
      $workers = User::whereIn('id',$worker_ids)->paginate(5);

      $message = ($header == 'en') ? 'List of Workers under selected project' : 'Lijst met werknemers onder het geselecteerde project' ;

      return response()->json([
        'status' => true,
        'message' => $message,
        'workers' => $workers,
        'worker_ids' => $worker_ids,
      ]);
    }
}
