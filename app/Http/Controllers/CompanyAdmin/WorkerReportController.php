<?php

namespace App\Http\Controllers\CompanyAdmin;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Project;

class WorkerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $worker=User::where('company_id',Auth::id())->where('name','!=',null)->where('role_id',3)->get();
        $project =DB::table('projects')->where('company_id',Auth::id())->get();
        $worker_detail=[];
        $worker_detailss=[];
        $project_detail=[];
        $worker_project=[];
        $project_start_end=[];
        $start_end=[];
        $all_joins=[];
        $summary_report=[];
        $message='';
        //dd($$worker_detail);
        return view('Company_Admin.worker_reports.index',compact('worker','project','worker_detail','project_detail','worker_detailss','message','project_start_end','worker_project','start_end','all_joins','summary_report'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getprojectsforworker($workerId)
    {

        $results = DB::table('days')
        ->join('projects', 'days.project_id', '=', 'projects.id')
        ->select('projects.name', 'projects.id')
        ->where('days.user_id', $workerId)
        ->distinct()
        ->get();

        return response()->json($results);


    }
    public function getWorkerTaskDetails(Request $request)
    {


        //$request->flash();

//        $this->validate($request, [
//            'StartDate' => 'required',
//            'EndDate' => 'required',
//        ]);

        $id = $request->worker_id;

        $worker = User::where('company_id', Auth::id())->where('name', '!=', null)->where('role_id', 3)->get();
        $project =DB::table('projects')->where('company_id',Auth::id())->get();
        $worker_detail=[];
        $project_detail=[];
        $worker_detailss=[];
        $worker_project=[];
        $project_start_end=[];
        $start_end=[];
        $all_joins=[];
        $summary_report=[];
        $message='';
        if ($request->worker_id !==null && ($request->StartDate ==null || $request->EndDate ==null) && $request->project_id==null){
            $worker_detail = DB::table('users')

                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
//            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([['user_id', $id]])

                //Filters

                ->select('users.name as worker_name', 'days.project_id','locations.name as location_name', 'tasks.name as tasks_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
              //  ->groupBy('tasks.name')
              //->distinct("tasks.name")
                ->get();


            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_start_end'=>$project_start_end,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }



        elseif ($request->project_id !== null && ($request->StartDate ==null || $request->EndDate ==null) && $request->worker_id==null) {

            $project_detail = DB::table('projects')
                ->join('days', 'projects.id', '=', 'days.project_id')
                ->join('tasks', 'tasks.id', '=', 'days.task_id')
                ->join('users','users.id','=','days.user_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')

//                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
//                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
//                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
//                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
////            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([['locations.project_id', $request->project_id]])

                //Filters
                ->select( 'days.project_id','tasks.name as task_name','locations.name as locations_name','users.name as worker_name','time_cards.check_in_time','time_cards.check_out_time','time_cards.total_time', 'projects.name as project_name')
                ->get();

            //dd($$worker_detail);

            return view('Company_Admin.worker_reports.index')->with(['project_detail'=>$project_detail,'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detail'=>$worker_detail,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }







        elseif($request->worker_id !== null && $request->StartDate !== null && $request->EndDate !== null && $request->project_id==null){

            $worker_detailss = DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
//            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([
                    ['user_id', $id],
//                    ['time_cards.created_at',$request->StartDate],
//                    ['time_cards.updated_at',$request->EndDate]
                ])
                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters
                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
                ->orderByDesc('time_cards.created_at')
               //  ->groupBy('taskss_name')

                ->get();

//            dd($$worker_detail);
            //dd($worker_detailss);
            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }



        elseif($request->worker_id !== null && $request->project_id!==null  && $request->StartDate == null && $request->EndDate == null){

            $worker_project = DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
//            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([
                    ['user_id',$request->worker_id],
                    ['days.project_id',$request->project_id],
//                    ['time_cards.created_at',$request->StartDate],
//                    ['time_cards.updated_at',$request->EndDate]
                ])
//                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
//                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters
//                ->distinct("tasks.name")
                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
                               // ->groupBy('taskss_name')

                ->orderByDesc('time_cards.created_at')
                ->get();




//            dd($$worker_detail);
            //dd($worker_detail);
            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }


        elseif($request->project_id !== null && $request->StartDate !== null && $request->EndDate !== null && $request->worker_id==null){

            $project_start_end = DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
//            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([
                    ['days.project_id',$request->project_id ],
//                    ['time_cards.created_at',$request->StartDate],
//                    ['time_cards.updated_at',$request->EndDate]
                ])
                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters
//                ->distinct("tasks.name")
                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
                ->orderByDesc('time_cards.created_at')
                ->get();

            //dd($$worker_detail);
            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }

        elseif($request->StartDate !== null && $request->EndDate !== null && $request->worker_id==null && $request->project_id==null){

            $start_end = DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
//            ->join('time_cards','tasks.id', '=', 'time_cards.task_id')
                ->where([
                    ['users.company_id',Auth::id()],
//                    ['time_cards.created_at',$request->StartDate],
//                    ['time_cards.updated_at',$request->EndDate]
                ])
                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters
//                ->distinct("tasks.name")
                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
                ->orderByDesc('time_cards.created_at')
                ->get();

            //dd($$worker_detail);
            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins,'summary_report'=>$summary_report]);

        }

        elseif( $request->worker_id!==null && $request->project_id!==null && $request->StartDate !== null && $request->EndDate !== null){


            $all_joins= DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
                ->where([
                    ['user_id',$request->worker_id ],
                    ['days.project_id',$request->project_id ],
                ])
                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters
               // ->distinct("tasks.name")
                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time', 'time_cards.total_time')
//                ->groupBy('time_cards.total_time')

                ->orderByDesc('time_cards.created_at')

                ->get();




//            $total=[];
//            $i=0;
//
//$r=[];
//            foreach ($all_joins as $details){
//                $r[$i]=$details;
//
//
//
//                $countName[]=$details->taskss_name;
//                $total[$i]=$details->total_time;
//                $i++;
//            }
//
//
//            $taskQuantity=array_count_values($countName);

            $summary_report= DB::table('users')
                ->leftjoin('days', 'users.id', '=', 'days.user_id')
                ->leftjoin('projects', 'projects.id', '=', 'days.project_id')
                ->leftjoin('tasks', 'tasks.id', '=', 'days.task_id')
                ->leftjoin('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->leftjoin('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->join('locations','locations.project_id','=','projects.id')
                ->where([
                    ['user_id',$request->worker_id ],
                    ['days.project_id',$request->project_id ],
                    ['week_cards.id','time_cards.week_cards_id']
                ])
                ->whereDate('time_cards.created_at', '>=',$request->StartDate)
                ->whereDate('time_cards.updated_at','<=',$request->EndDate)

                //Filters

                ->select('users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time'
//                    ,'sec_to_time(sum(time_to_sec(time_cards.total_time)))'
                )
//                ->selectRaw(
//                    'sec_to_time(sum(time_to_sec(time_cards.total_time))) as total'
////                    'users.name as worker_name', 'days.project_id','locations.name as locations_name', 'tasks.name as taskss_name', 'projects.name as project_name', 'time_cards.check_in_time', 'time_cards.check_out_time'
//                )

//                ->selectRaw('sec_to_time(sum(time_to_sec(time_cards.total_time)))')
                //->groupBy('time_cards.week_cards_id')
//                ->orderByDesc('time_cards.created_at')
                ->get();
            //dd($summary_report);
     //dd($worker_detail);
            return view('Company_Admin.worker_reports.index')->with(['worker_detail'=>$worker_detail,'project_detail'=>$project_detail, 'id'=>$id,'project'=>$project,'worker'=>$worker,'worker_detailss'=>$worker_detailss,'message'=>$message,'worker_project'=>$worker_project,'project_start_end'=>$project_start_end,'start_end'=>$start_end,'all_joins'=>$all_joins, 'summary_report'=>$summary_report]);

        }

        else{
            return redirect()->back()->with('message','The Field is required');
        }


//        return response()->json([
//            'worker'=>$worker
//        ]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
