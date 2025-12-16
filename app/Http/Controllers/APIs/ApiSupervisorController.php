<?php

namespace App\Http\Controllers\APIs;
use App\Models\StaffingCompany\Contact;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\WeekState;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;
use FontLib\Table\Type\name;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Day;
use App\Models\User;
use App\Models\Project;
use App\Models\TimeCard;
use App\Models\WeekCard;
use App\Models\Leave;
use App\Models\LeaveUser;
use App\Models\Notification;
use App\Models\InspectionReview;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveUserResource;
use App\Models\StaffingCompany\StaffingProject;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class ApiSupervisorController extends Controller
{
    public function myworkers(Request $request)
    {
        $header = $request->header('language');
        $id = Auth::user()->id;
        $workers = User::where('reports_to_id', '=', $id)->paginate(5);

        $message = ($header == 'en') ? 'List of Workers under supervisor' : 'Lijst van medewerkers onder leiding';

        return response()->json([
            'status' => true,
            'message' => $message,
            'workers' => $workers,
        ]);
    }

    public function supervisorworkers(Request $request, $id)
    {
        $header = $request->header('language');
        $workers = User::where('reports_to_id', '=', $id)->paginate(5);

        $message = ($header == 'en') ? 'List of Workers under supervisor' : 'Lijst van medewerkers onder leiding';

        return response()->json([
            'status' => true,
            'message' => $message,
            'workers' => $workers,
        ]);
    }

    public function projectworkers(Request $request, $id)
    {
        $header = $request->header('language');
        $projectJobs = Day::where('project_id', '=', $id)
            // ->where('location_id', '=', $location_id)
            ->distinct('user_id')
            ->pluck('user_id');
        $workers = User::whereIn('id', $projectJobs)->paginate(5);

        $message = ($header == 'en') ? 'List of workers on project' : 'Lijst van werknemers op project';

        return response()->json([
            'status' => true,
            'message' => $message,
            'workers' => $workers,
        ]);
    }

    public function singleNotification($title, $body, $token, $reciverId, $senderId)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        //Payload data ///
        $my_data = [
            'title' => $title,
            'body' => $body,
            'NotificationReceiverId' => $reciverId,
            'NotificationSenderId' => $senderId,
        ];
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $my_data]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
        return $downstreamResponse->numberSuccess();
    }

    public function supleavemark(Request $request)
    {
        //check leave type if sick leave then set whole status approved and in case of holliday
        $header = $request->header('language');

        $created_time = Carbon::now()->setTimezone('Europe/Amsterdam');
        $leave_user = LeaveUser::select('approved_by', 'user_id')
            ->where('leave_id', '=', $request->leave_id)
            ->first();

        $leave = Leave::find($request->leave_id);

        $user = User::find($leave_user->user_id);
        $token = User::where('id', '=', $leave_user->user_id)
            ->pluck('fcm_token')->toArray();
        // $token = $user->fcm_token;
        $receiverId = $user->id;
        $sender = Auth::user();
        $senderId = Auth::user()->id;

        if ($leave->leave_type_id == 1) {
            // for sick leave
            $leave->status_id = 2; //leave fully approved
            $leave->save(); //leave fully approved

            $record = json_decode($leave_user->approved_by);
            $record[0]->status = true;
            $record[0]->comment = $request->comment;
            $record[1]->status = true;
            $record[1]->comment = "Approved";
            $record = json_encode($record);

            if ($header == 'en') {
                $title = 'Leave Approved';
                $body = 'Sick Leave Approved by Supervisor ' . $sender->name . '. :Created at :' . $created_time;
            } else {
                $title = 'Goedgekeurd laten';
                $body = 'Ziekteverlof Goedgekeurd door Supervisor ' . $sender->name . '. :Tijd :' . $created_time;
            }

        } else {
            // for holliday leave
            $record = json_decode($leave_user->approved_by);
            $record[0]->comment = $request->comment;
            $record[0]->status = $request->approved;
            $record = json_encode($record);

            $title = '';
            $body = '';

            if (!$request->approved) {
                $leave->status_id = 3; //leave fully Rejected
                $leave->save();

                if ($header == 'en') {
                    $title = 'Leave not approved';
                    $body = 'Holliday Leave Rejected by Supervisor ' . $sender->name . '. :Created at :' . $created_time;
                } else {
                    $title = 'Verlof niet goedgekeurd';
                    $body = 'Vakantieverlof afgewezen door supervisor ' . $sender->name . '. :Tijd :' . $created_time;
                }
            } else {
                $leave->status_id = 2; //leave fully accepted
                $leave->save();

                if ($header == 'en') {
                    $title = 'Leave Approved';
                    $body = 'Holliday Leave Approved by Supervisor ' . $sender->name . '. :Created at :' . $created_time;
                } else {
                    $title = 'Goedgekeurd laten';
                    $body = 'Vakantieverlof goedgekeurd door de supervisor ' . $sender->name . '. :Tijd :' . $created_time;
                }
            }
        }//else holliday leave

        $notification = new Notification;
        $notification->title = $title;
        $notification->body = $body;
        $notification->remarks = $request->comment;
        $notification->status = 0;
        $notification->reciever_id = $receiverId;
        $notification->sender_id = $senderId;
        $notification->created_at = Carbon::now()->setTimezone('Europe/Amsterdam');
        $notification->save();

        $status = $this->singleNotification($title, $body, $token, $receiverId, $senderId);

        LeaveUser::where('leave_id', '=', $request->leave_id)
            ->update(['approved_by' => $record]);

        $message = ($header == 'en') ? "Leave acknowledged by supervisor" : "Verlof ontvangen door supervisor";

        return response()->json([
            'status' => true,
            'message' => $message,
            'NotificationStatus' => $status,
            'NotificationReceiver' => $receiverId,
            'NotificationToken' => $token,
        ]);
    }

    public function manleavemark(Request $request)
    {
        $created_time = Carbon::now()->setTimezone('Europe/Amsterdam');
        $header = $request->header('language');
        $leave_user = LeaveUser::select('approved_by', 'user_id')
            ->where('leave_id', '=', $request->leave_id)
            ->first();

        $leave = Leave::find($request->leave_id);
        $user = User::find($leave_user->user_id);
        $token = $user->fcm_token;
        $receiverId = $user->id;
        $sender = Auth::user();
        $senderId = Auth::user()->id;
        $record = json_decode($leave_user->approved_by);
        $record[1]->comment = $request->comment;
        $record[1]->status = $request->approved;

        $title = '';
        $body = '';

        if ($record[0]->status && $record[1]->status) {
            $leave->status_id = 2; //approved
            if ($header == 'en') {
                $title = 'Leave Approved';
                $body = 'Holliday Leave Approved by Manager ' . $sender->name . '. :Created at :' . $created_time;
            } else {
                $title = 'Goedgekeurd laten';
                $body = 'Ziekteverlof Goedgekeurd door Manager ' . $sender->name . '. :Tijd :' . $created_time;
            }

        } else if (!$record[1]->status) {
            $leave->status_id = 3; //leave fully Rejected
            if ($header == 'en') {
                $title = 'Leave rejected';
                $body = 'Holliday leave rejected by manager ' . $sender->name . '. :Created at :' . $created_time;
            } else {
                $title = 'Verlof niet goedgekeurd';
                $body = 'Vakantieverlof afgewezen door manager ' . $sender->name . '. :Tijd :' . $created_time;
            }

        }
        $leave->save();

        $status = $this->singleNotification($title, $body, $token, $receiverId, $senderId);

        $notification = new Notification;
        $notification->title = $title;
        $notification->body = $body;
        $notification->remarks = $request->comment;
        $notification->status = 0;
        $notification->reciever_id = $receiverId;
        $notification->sender_id = $senderId;
        $notification->created_at = Carbon::now()->setTimezone('Europe/Amsterdam');
        $notification->save();

        $record = json_encode($record);
        LeaveUser::where('leave_id', '=', $request->leave_id)
            ->update(['approved_by' => $record]);

        $message = ($header == 'en') ? "Leave Acknowledged by Manager" : "Verlof ontvangen door manager";
        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    public function workerleavelist(Request $request, $workerId)
    {   //return the list of leaves by specific worker
        $header = $request->header('language');
        $message = ($header == 'en') ? "List of Leaves by Worker" : "Lijst met bladeren per werknemer";

        return response()->json([
            'status' => true,
            'message' => $message,
            'leaves' => LeaveUserResource::collection(LeaveUser::where('user_id', '=', $workerId)->orderBy('created_at', 'DESC')->get()),
        ]);
    }

    public function supervisorProjects(Request $request)
    {
        //return the list of projects supervised by specific supervisor
        $header = $request->header('language');
        $supervisor_id = _user()->id;
        $projects = StaffingProject::where('performer', $supervisor_id)->paginate(5);
        $message = ($header == 'en') ? "List of Supervisor Projects" : "Lijst met supervisor projecten";

        return response()->json([
            'status' => true,
            'message' => $message,
            'projects' => $projects,
        ]);
    }

    public function completedtasksofday(Request $request)
    {
        //return the list of completed tasks by specific worker on given date
        $header = $request->header('language');
        $worker_id = $request->worker_id;
        $project_id = $request->project_id;
        $start_date = Carbon::parse($request->start_date)
            ->startOfDay()
            ->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)
            ->endOfDay()
            ->toDateTimeString();
        // join on days, tasks, weekcard, timecard
        $tasks = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
//            ->join('areas','areas.id','=','days.area_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->where('time_cards.ratings', '=', Null)
            ->select('tasks.*')
//            ->distinct('tasks.id')
            ->get();

        //join for count of uninspected tasks
        $uninspected_count = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->where('time_cards.ratings', '=', Null)
            ->select('tasks.id')
            ->distinct('tasks.id')
            ->get();

        //join for count of inspected tasks
        $inspected_count = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->where('time_cards.ratings', '!=', Null)
            ->select('tasks.id')
            ->distinct('tasks.id')
            ->get();


        // join for count of inspected tasks
        $total_count = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->select('tasks.id')
            ->distinct('tasks.id')
            ->get();

        foreach ($tasks as $key => $task) {
            $task_count = DB::table('days')
                ->join('tasks', 'tasks.id', '=', 'days.task_id')
                ->join('areas', 'areas.id', '=', 'days.area_id')
                ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
                ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
                ->where('days.task_id', '=', $task->id)
                ->where('days.project_id', '=', $project_id)
                ->whereBetween('time_cards.created_at', array($start_date, $end_date))
                ->whereNull('time_cards.ratings')
                ->select('areas.*')
                ->get();


            $task->name = $task->name . ' - ' . $task_count[0]->name;
//                                    $task->name = $task->name;

        }

        $message = ($header == 'en') ? "List of Completed Tasks by Worker on specific Date and Project" : "Lijst met voltooide taken per werknemer op specifieke datum en project";

        $uninspected_task_floor_count = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('project_jobs', 'project_jobs.id', '=', 'days.job_id')
            ->join('floors', 'floors.id', '=', 'project_jobs.floor_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->where('time_cards.ratings', '=', Null)
            ->select('tasks.name as task_name', 'floors.name as floor_name', DB::raw('Count(days.task_id) AS task_count'))
            ->groupBy('tasks.name', 'floors.name')
            ->get();

        return response()->json([
            'status' => true,
            'message' => $message,
            'completed_tasks_count' => sizeOf($total_count),
            'not_ins_count' => sizeOf($uninspected_count),
            'ins_count' => sizeOf($inspected_count),
            'uninspected_task_floor_count' => $uninspected_task_floor_count,
            'tasks' => $tasks,
        ]);
    }

    public function ratetask(Request $request)
    {
        Carbon::now()->setTimezone('Europe/Amsterdam');
        $header = $request->header('language');
        $task_id = $request->task_id;
        $ratings = $request->ratings;
        $remarks = $request->remarks;
        $worker_id = $request->worker_id;
        $project_id = $request->project_id;
        $start_date = Carbon::parse($request->start_date)
            ->startOfDay()
            ->toDateTimeString();
        $end_date = Carbon::parse($request->end_date)
            ->endOfDay()
            ->toDateTimeString();

        $user = User::find($worker_id);
        $token = $user->fcm_token;
        $receiverId = $worker_id;
        $senderId = Auth::user()->id;

        if ($header == 'en') {
            $title = "New Rating Work";
            $body = "Rating on completed Work by Inspector " . $user->name;
        } else {
            $title = "Nieuw werk beordeling";
            $body = "Beoordeling van voltooide werkzaamheden door inspecteur " . $user->name;
        }
        //
        // return response()->json([
        //     'token set' => isset($user->fcm_token),
        //     'token empty' => empty($user->fcm_token),
        // ]);

        if (!empty($user->fcm_token)) {
            $status = $this->singleNotification($title, $body, $token, $receiverId, $senderId);
        }


        $notification = new Notification;
        $notification->title = $title;
        $notification->body = $body;
        $notification->remarks = $remarks;
        $notification->status = 0;
        $notification->reciever_id = $worker_id;
        $notification->sender_id = $senderId;
        $notification->created_at = Carbon::now()->setTimezone('Europe/Amsterdam');
        $notification->save();

        $tasks = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->where('days.task_id', '=', $task_id)
            ->whereBetween('time_cards.created_at', array($start_date, $end_date))
            ->where('time_cards.ratings', '=', Null)
            ->select('time_cards.id')
            ->pluck('time_cards.id');

        TimeCard::whereIn('id', $tasks)->update([
            'ratings' => $ratings,
            'remarks' => $remarks,
            'updated_at' => Carbon::now(),
        ]);

        $message = ($header == 'en') ? "Ratings on Tasks done by Inspector" : "Beoordelingen voor taken uitgevoerd door Inspecteur";

        return response()->json([
            'status' => true,
            'message' => $message,
            'tasks' => $tasks,
            'request' => $request->all(),
        ]);
    }

    public function inspectionreview(Request $request)
    {
        $header = $request->header('language');
        $inspector_id = Auth::user()->id;
        $project_id = $request->project_id;
        $worker_id = $request->worker_id;
        $updated_date = Carbon::now()->toDateString();
        $comp_id = Auth::user()->company_id;
        Carbon::now()->setTimezone('Europe/Amsterdam');

        $tasks = DB::table('days')
            ->join('tasks', 'tasks.id', '=', 'days.task_id')
            ->join('week_cards', 'week_cards.days_id', '=', 'days.id')
            ->join('time_cards', 'time_cards.week_cards_id', '=', 'week_cards.id')
            ->where('days.user_id', '=', $worker_id)
            ->where('days.project_id', '=', $project_id)
            ->where('time_cards.updated_at', 'like', $updated_date . '%')
            ->where('time_cards.inspection_review_id', '=', Null)
            ->select('time_cards.id')
            ->pluck('time_cards.id');

        $message = ($header == 'en') ? "Sorry, No any completed job exist on this date for inspection." : "Sorry, er zijn geen voltooide taken op deze datum voor inspectie.";

        if (empty($tasks[0])) {
            return response()->json([
                'status' => false,
                'updated_date' => $updated_date,
                'message' => $message,
            ]);
        }

        $ins_review = new InspectionReview;
        $ins_review->review = Null;
        $ins_review->project_id = $request->project_id;
        $ins_review->worker_id = $request->worker_id;
        $ins_review->inspector_id = $inspector_id;
        $ins_review->company_id = $comp_id;
        $ins_review->average_score = Null;
        $ins_review->completed_tasks_count = Null;
        $ins_review->created_at = Carbon::now();
        $ins_review->save();

        TimeCard::whereIn('id', $tasks)->update([
            'inspection_review_id' => $ins_review->id,
        ]);

        //loop for all workers of reviewed jobs
        $user = User::find($worker_id);
        $token = User::where('id', '=', $worker_id)
            ->pluck('fcm_token')->toArray();
        // $token = $user->fcm_token;
        $receiverId = $user->id;
        $senderId = Auth::user()->id;
        if ($header == 'en') {
            $title = "Review on Work";
            $body = "Inspection Review on completed Work by Inspector " . $user->name . '. :Created at :' . $created_time;
        } else {
            $title = "Beoordeling op werk";
            $body = "Inspectiecontrole van voltooide werkzaamheden door inspecteur " . $user->name . '. :Tijd :' . $created_time;
        }

        $status = $this->singleNotification($title, $body, $token, $receiverId, $senderId);

        $notification = new Notification;
        $notification->title = $title;
        $notification->body = $body;
        $notification->status = 0;
        $notification->reciever_id = $worker_id;
        $notification->sender_id = $senderId;
        $notification->created_at = Carbon::now()->setTimezone('Europe/Amsterdam');
        $notification->save();

        $message = ($header == 'en') ? "Inspection Review Submitted by Inspector" : "Inspectiebeoordeling ingediend door inspecteur";

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    public function getinspectionreview(Request $request)
    {
        $header = $request->header('language');
        $inspector_id = Auth::user()->id;
        $project_id = $request->project_id;
        $worker_id = $request->worker_id;
        $date = $request->date;

        $ins_review = DB::table('inspections_review')
            ->where('inspector_id', '=', $inspector_id)
            ->where('project_id', '=', $project_id)
            ->where('worker_id', '=', $worker_id)
            ->where('created_at', 'like', $date . '%')
            ->first();

        if ($header == 'en') {
            $message = ($ins_review) ? "Review Found" : 'No Review Found';
        } else {
            $message = ($ins_review) ? "Beoordeling gevonden" : 'Geen beoordeling gevonden';
        }

        return response()->json([
            'status' => ($ins_review) ? true : false,
            'message' => $message,
            'ins_review' => ($ins_review) ? $ins_review : 'No review Found',
        ]);
    }

    //new build
    public function tracking2weeks(Request $request)
    {
        $user=_user()->id;
        $contactId = Contact::where('user_id', '=', $user)->value('id');
        $currentWeekNo=_currentWeekNo() ;
        $perviousWeekNo=$currentWeekNo-1 ;
        $weeks=[$currentWeekNo,$perviousWeekNo];
        $weekstates = WeekState::whereIn('week_no', $weeks)
            ->whereHas('projects', function ($query) use ($contactId) {
                $query->where('performer', $contactId);
            })->with(
                'weekCards.timeCards.personnel:id,picture,first_name')
            ->get();

       return response()->json([$currentWeekNo,$perviousWeekNo,$weekstates]) ;

    }

    public function edit_worker_hours(Request $request)
    {
        $validated = $request->validate([
            'week_state_id' => 'required',
            'personnel_id' => 'required',
            'week_no' => 'required',
        ]);

        $week_card = SfWeekCard::where('week_state_id', $request->week_state_id)
            ->where('personnel_id', $request->personnel_id)
            ->where('week_no', $request->week_no)->first();

        if (!$week_card) {
            return response()->json([
                'status' => false,
                'message' => 'Week card not found.',
            ], 404);
        }

        $week_card->hours_1=(int)$request->monday;
        $week_card->hours_2=(int)$request->tuesday;
        $week_card->hours_3=(int)$request->wednesday;
        $week_card->hours_4=(int)$request->thursday;
        $week_card->hours_5=(int)$request->friday;
        $week_card->hours_6=(int)$request->saturday;
        $week_card->hours_7=(int)$request->sunday;

        //update total hours
        $total_hours=$week_card->hours_1+
        $week_card->hours_2+
        $week_card->hours_3+
        $week_card->hours_4+
        $week_card->hours_5+
        $week_card->hours_6+
        $week_card->hours_7;

        $week_card->total_hours=$total_hours;
        $week_card->update();

        return response()->json([
            'status' => true,
            'data' => $week_card,
        ]);

    }


}
