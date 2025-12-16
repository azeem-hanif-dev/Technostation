<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\Comment;
use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\SCTimeCard;
use App\Models\StaffingCompany\SfWeekCard;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\WeekState;
use App\Models\StaffingCompany\WeekStateDocument;
use App\Models\StaffingCompany\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class WeekStateController extends Controller
{
    public function index()
    {
        $years = _getPastYears(10);
        $week_states = WeekState::all()->sortByDesc('created_at');
        $project_names = '';

        return view('StaffingCompany.WeekState.index', compact('years', 'week_states', 'project_names'));
    }

    public function create()
    {
        $projects = StaffingProject::orderBy('name')->get();
        $current_year = Carbon::now()->year;
        $current_week = Carbon::now()->weekOfYear;
        if ($current_week < 10) {
            $current_week = '0' . $current_week;
        }
        $week_no = $current_year . $current_week;
        $translations = __('Staffing_Company/Week_State/crud');

        return view('StaffingCompany.WeekState.create', compact('projects', 'week_no', 'translations'));
    }

    public function store(Request $request)
    {

        $project = StaffingProject::find($request->project);


        if ($this->weekStateExist($project, $request->week_no)) {
            return response()->json([
                'message' => 'Week State already exists',
                'status'  => 0,
            ], 200);
        } elseif ($this->projectPlanningExists($project, $request->week_no)) {
            $approved = 0;
            $via_work_sheet = 0;

            if ($request->approved) {
                $approved = 1;
            }

            if ($request->via_worksheet) {
                $via_work_sheet = 1;
            }

            $week_state = WeekState::create([
                'week_no' => $request->week_no,
                'delay_date' => $request->delay_date,
                'receive_date' => $request->received_date,
                'invoice_date' => $request->invoice_date,
                'status' => $request->status ?? "NULL",
                'approved' => $approved,
                'via_worksheet' => $via_work_sheet,
                'comments' => $request->comments,
                'internal_notes' => $request->internal_notes,
            ]);

            $project->notes = $request->notes;
            $project->approval = $request->approval;
            $project->save();

            $week_state->projects()->attach($request->project);

            if ($request->document_logs) {
                foreach ($request->document_logs as $document) {
                    if ($document['file']) {

                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();

                        $file->storeAs('week_state_docs', $fileName, 'public');

                        $week_state->documents()->create([
                            'type' => $document['doc_type'],
                            'expiry_date' => $document['expiry'],
                            'file' => $fileName,
                        ]);
                    }
                }
            }

            return response()->json([
                // 'message' => 'Week state created successfully',
                'message' => __('Staffing_Company/Week_State/crud.week_state_create'),
                'status'  => 1,
                'id'  => $week_state->id,
            ], 200);
        } else {
            return response()->json([
                // 'message' => 'Project Planning not found',
                'message' => __('Staffing_Company/Week_State/crud.planning_not_fond'),
                'status'  => 0,
            ], 200);
        }
    }

    public function show(WeekState $week_state)
    {
        $weekState = $week_state->load('documents');

        // Add the document paths to the loaded documents
        $weekState->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/week_state_docs/' . $document->file);
            return $document;
        });

        return response()->json($week_state);
    }

    public function view($id, Request $request)
    {
        $week_state = WeekState::with('weekCards')->find($id);
        $project = StaffingProject::find($request->project_id);

        return view('StaffingCompany.WeekState.show', compact('week_state', 'project'));
    }
    // public function edit($id, Request $request)
    // {
    //     $project_id = $request->project_id;
    //     //$projects = StaffingProject::orderBy('name')->get();
    //     $projects = StaffingProject::where('active', true)
    //                        ->orderBy('name')
    //                        ->get();

    //     $personnel_ids = EmployeeProjectPlanning::where('project_id',$project_id)
    //                 ->pluck('employee_id')
    //                 ->unique()
    //                 ->toArray();

    //     $personnels = Personnel::whereIn('id',$personnel_ids)->get(); //project wise personnels will be show
    //     $comments = Comment::all();
    //     $translations = __('Staffing_Company/Week_State/crud');

    //     return view('StaffingCompany.WeekState.update',compact('id','projects','personnels','comments','project_id','translations'));
    //
    public function edit_week($id, Request $request)
    {
        $project_id = $request->project_id;
        // $projects = StaffingProject::where('active', true)
        //     ->orderBy('name')
        //     ->get();
        $projects = StaffingProject::with('projectPerformer')
        ->where('active', true)
        ->orderBy('name')
        ->get();
        $personnel_ids = EmployeeProjectPlanning::where('project_id', $project_id)
            ->pluck('employee_id')
            ->unique()
            ->toArray();


        $week_cards = SfWeekCard::where('week_state_id', $id)
            ->select('id', 'personnel_id', 'hours_1', 'hours_2', 'hours_3', 'hours_4', 'hours_5', 'hours_6', 'hours_7', 'total_hours', 'customer', 'cost', 'directing', 'comments', 'time_approve')
            ->get();
        // Retrieve personnels based on project ID
        $personnels = Personnel::whereIn('id', $personnel_ids)->get();
        $comments = Comment::all();
        $translations = __('Staffing_Company/Week_State/crud');
        //        if user has role StaffingCompany the allow approved check box to approve week state
        $approved_allow = _user()->role_id === 14 ? 1 : 0;

        return view('StaffingCompany.WeekState.update', compact('id', 'projects', 'personnels', 'comments', 'project_id', 'translations', 'approved_allow', 'week_cards'));
    }

    public function update(Request $request, WeekState $week_state)
    {
        $project = StaffingProject::find($request->project);

        if ($this->projectPlanningExists($project, $request->week_no)) {
            $week_state->update([
                'week_no' => $request->week_no,
                // 'invoice_no' => $request->invoice_no,
                'delay_date' => $request->delay_date,
                'receive_date' => $request->receive_date,
                'invoice_date' => $request->invoice_date,
                'status' => $request->status ?? "NULL",
                'approved' => $request->approved,
                'via_worksheet' => $request->via_worksheet,
                'comments' => $request->comments,
                'internal_notes' => $request->internal_notes,
            ]);

            if ($project) {
                $project->notes = $request->notes;
                $project->approval = $request->approval;
                $project->save();
            }

            if (!$week_state->projects()->where('staffing_project_id', $request->project)->first()) {
                // Add logging to verify detach and attach operations
                Log::info('Detaching old project:', ['old_project' => $request->old_project_id]);
                $week_state->projects()->detach($request->old_project_id);

                Log::info('Attaching new project:', ['new_project' => $request->project]);
                $week_state->projects()->attach($request->project);
            }

            if ($request->removedPersonnelIds) {
                SfWeekCard::whereIn('id', $request->removedPersonnelIds)->delete();
                SCTimeCard::whereIn('sf_week_card_id', $request->removedPersonnelIds)->delete();
            }

            //create new personnel week card
            if ($request->personnel_logs) {
                foreach ($request->personnel_logs as $personnel) {
                    $condition = $personnel['personnel'] ?? 0;
                    if ($condition != 0) {
                        $week_state->weekCards()->create([
                            'personnel_id' => $personnel['personnel'],
                            'week_no' => $request->week_no,
                            'comments' => $personnel['wage'],
                            'hours_1' => $personnel['hours_1'],
                            'hours_2' => $personnel['hours_2'],
                            'hours_3' => $personnel['hours_3'],
                            'hours_4' => $personnel['hours_4'],
                            'hours_5' => $personnel['hours_5'],
                            'hours_6' => $personnel['hours_6'],
                            'hours_7' => $personnel['hours_7'],
                            'total_hours' => $personnel['total_hours'],
                            'customer' => $this->convertCommaSeparatedDecimal($personnel['rate']),
                            'cost' => $this->convertCommaSeparatedDecimal($personnel['cost']),
                            'directing' => $personnel['directing'],
                        ]);
                    }
                }
            }

            //update personnel week card
            if ($request->personnel_update_logs) {
                foreach ($request->personnel_update_logs as $personnel) {
                    if ($personnel['week_card_id']) {
                        SfWeekCard::where('id', $personnel['week_card_id'])->update([
                            'personnel_id' => $personnel['personnel'],
                            'week_no' => $request->week_no,
                            'comments' => $personnel['wage'],
                            'hours_1' => $personnel['hours_1'],
                            'hours_2' => $personnel['hours_2'],
                            'hours_3' => $personnel['hours_3'],
                            'hours_4' => $personnel['hours_4'],
                            'hours_5' => $personnel['hours_5'],
                            'hours_6' => $personnel['hours_6'],
                            'hours_7' => $personnel['hours_7'],
                            'total_hours' => $personnel['total_hours'],
                            'customer' => $this->convertCommaSeparatedDecimal($personnel['rate']),
                            'cost' => $this->convertCommaSeparatedDecimal($personnel['cost']),
                            'directing' => $personnel['directing'],
                        ]);
                    }
                }
            }

            if ($request->document_logs) {
                foreach ($request->document_logs as $document) {

                    if ($document['file']) {

                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();

                        $file->storeAs('week_state_docs', $fileName, 'public');

                        $week_state->documents()->create([
                            'type' => $document['doc_type'],
                            'expiry_date' => $document['expiry'],
                            'file' => $fileName,
                        ]);
                    }
                }
            }

            if ($request->removedDocumentIds) {
                WeekStateDocument::whereIn('id', $request->removedDocumentIds)->delete();
            }

            return response()->json([
                // 'message' => 'Week state updated successfully!',
                'message' => __('Staffing_Company/Week_State/crud.week_state_update'),
                'status'  => 1,
            ], 200);
        } else {
            return response()->json([
                // 'message' => 'Project Planning not found!',
                'message' => __('Staffing_Company/Week_State/crud.planning_not_fond'),
                'status'  => 0,
            ], 200);
        }
    }

    public function destroy(WeekState $week_state, Request $request)
    {
        // dd($week_state);
        $week_state->projects()->detach($request->project_id);
        $week_state->delete();

        return redirect()->back()->with('message', 'Week State Deleted Successfully!');
    }

    public function search(Request $request)
    {
        $week_no = $request->week_no;
        $year = $request->year;
        $status = $request->status ? 1 : 0;

        $years = _getPastYears(10);
        $week_states = WeekState::where('week_no', $year . $week_no)->where('status', $status)->get();
        return view('StaffingCompany.WeekState.index', compact('years', 'week_states', 'week_no', 'year', 'status'));

        // return view('StaffingCompany.WeekState.index', compact('years', 'week_states'));
    }

    public function WeekCardPDF($id, $project_id, $directing)
    {


        $week_state = WeekState::with('weekCards.timeCards', 'weekCards.personnel')->find($id);
        //$project = StaffingProject::find($project_id);
        $project = StaffingProject::with('department')->find($project_id);
        $performer = Contact::find($project->performer);

        $performerName = $performer ? $performer->first_name . ' ' . $performer->last_name : 'N/A';
        $performerEmail = $performer ? $performer->email : 'N/A';
        $performerMobile = $performer ? $performer->mobile : 'N/A';

        $departmentName = $project->department ? $project->department->name : 'N/A';

        if (empty($week_state)) {
            return redirect('admin/404');
        }

        $pdf = PDF::loadView('StaffingCompany.WeekState.pdf', compact('week_state', 'directing', 'project', 'performerName', 'performerEmail', 'performerMobile', 'departmentName'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Weekstaat.pdf');
    }
    public function singleWeekCardPDF($weekStateId, $projectId, $personnelId)
    {
        $week_state = WeekState::with('weekCards.personnel', 'weekCards.timeCards')->findOrFail($weekStateId);
        $project = StaffingProject::with('department')->findOrFail($projectId);
        $weekCard = $week_state->weekCards->firstWhere('personnel_id', $personnelId);
        if (!$weekCard) {
            abort(404, 'No WeekCard found for selected personnel.');
        }

        $performer = Contact::find($project->performer);
        $performerName = $performer ? $performer->first_name . ' ' . $performer->last_name : 'N/A';
        $performerEmail = $performer->email ?? 'N/A';
        $performerMobile = $performer->mobile ?? 'N/A';
        $departmentName = $project->department->name ?? 'N/A';

        $pdf = PDF::loadView('StaffingCompany.WeekState.singlepdf', compact(
            'week_state',
            'weekCard',
            'project',
            'performerName',
            'performerEmail',
            'performerMobile',
            'departmentName'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Weekstaat_' . $weekCard->personnel->first_name . '.pdf');
    }
    public function WeekCardPDFSingle($id, $project_id, $directing, Request $request)
    {
        $personnel_id = $request->input('personnel_id');
        $week_state = WeekState::with([
            'weekCards' => function ($query) use ($personnel_id) {
                $query->where('personnel_id', $personnel_id);
            },
            'weekCards.timeCards',
            'weekCards.personnel'
        ])->find($id);

        $project = StaffingProject::with('department')->find($project_id);
        $performer = Contact::find($project->performer);

        $performerName = $performer ? $performer->first_name . ' ' . $performer->last_name : 'N/A';
        $performerEmail = $performer ? $performer->email : 'N/A';
        $performerMobile = $performer ? $performer->mobile : 'N/A';

        $departmentName = $project->department ? $project->department->name : 'N/A';

        if (empty($week_state)) {
            return redirect('admin/404');
        }

        $pdf = PDF::loadView('StaffingCompany.WeekState.pdf', compact('week_state', 'directing', 'project', 'performerName', 'performerEmail', 'performerMobile', 'departmentName'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Weekstaat.pdf');
    }


    public function weekWiseProjects()
    {
        $years = _getPastYears(10);
        $week_no = _currentWeekNo();

        $project_plannings = ProjectPlanning::with('staffingProjects')
            ->where('week_no', _currentWeekNo())
            ->get();

        $staffing_project_ids = $project_plannings->flatMap(function ($project) {
            return $project->staffingProjects->pluck('id');
        })->unique()->values();

        $projects = StaffingProject::with(['department', 'weekStates'])->whereIn('id', $staffing_project_ids)->get();

        $personnel_ids = EmployeeProjectPlanning::whereIn('project_id', $projects->pluck('id')->unique()->toArray())
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        $personnels = Personnel::whereIn('id', $personnel_ids)->get(); //project wise personnels will be show
        $comments = Comment::all();
        $translations = __('Staffing_Company/Week_State/crud');

        return view('StaffingCompany.WeekState.weekly_project_index', compact('years', 'week_no', 'projects', 'personnels', 'comments', 'translations'));
    }

    public function searchWeekWiseData($week_no)
    {
        $years = _getPastYears(10);

        $project_plannings = ProjectPlanning::with('staffingProjects')
            ->where('week_no', $week_no)
            ->get();

        $staffing_project_ids = $project_plannings->flatMap(function ($project) {
            return $project->staffingProjects->pluck('id');
        })->unique()->values();

        //$projects = StaffingProject::with(['department','weekStates'])->whereIn('id', $staffing_project_ids)->get();

        // Get staffing projects and their related departments and week states
        // $projects = StaffingProject::with(['department', 'weekStates' => function ($query) use ($week_no) {
        // // Filter week states to include only the specified week number
        // $query->where('week_no', $week_no);
        // }])->whereIn('id', $staffing_project_ids)->get();

        $projects = StaffingProject::with(['department', 'weekStates' => function ($query) use ($week_no) {
            $query->where('week_no', $week_no)->with('weekCards');
        }])->whereIn('id', $staffing_project_ids)->get();

        $personnel_ids = EmployeeProjectPlanning::whereIn('project_id', $projects->pluck('id')->unique()->toArray())
            ->pluck('employee_id')
            ->unique()
            ->toArray();

        $personnels = Personnel::whereIn('id', $personnel_ids)->get(); //project wise personnels will be show
        $comments = Comment::all();
        $translations = __('Staffing_Company/Week_State/crud');

        return response()->json([
            'projects' => $projects,
            'personnels' => $personnels,
            'comments' => $comments,
            'translations' => $translations,
            'years' => $years,
            'week_no' => $week_no,
            'status' => 'success'
        ], 200);
    }

    private function convertCommaSeparatedDecimal($string_value)
    {
        $decimal_string = str_replace(',', '.', $string_value);

        return floatval($decimal_string);
    }

    private function projectPlanningExists($project, $week_no)
    {
        return $project->projectPlannings()->where('week_no', $week_no)->exists();
    }

    private function weekStateExist($project, $week_no)
    {
        return $project->weekStates()->where('week_no', $week_no)->exists();
    }

    public function weekStatesReport(Request $request)
    {
        $report_type = 'null';

        if ($request->status == 'all') {
            $week_no = $request->week_no;
            if ($week_no < 10) {
                $week_no = '0' . $week_no;
            }

            $year = $request->year;
            $year_week_no = $year . $week_no;
            $report_type = 'all_week_states';
            $week_states = WeekState::with('projects')->where('week_no', $year_week_no)->get();

            $pdf = PDF::loadView('StaffingCompany.WeekState.week_states_report', compact('report_type', 'week_states', 'year', 'week_no'));
        } else {
            $report = $request->report_type;
            $start_week_no = $request->open_start_week_no;
            $end_week_no = $request->open_end_week_no;
            $year = $request->open_year;

            if ($start_week_no < 10) {
                $start_week_no = '0' . $start_week_no;
            }

            if ($end_week_no < 10) {
                $end_week_no = '0' . $end_week_no;
            }

            $start_year_week_no = $year . $start_week_no;
            $end_year_week_no = $year . $end_week_no;
            $week_nos = [];
            $projects = [];

            $week_states = WeekState::with('projects')
                ->where('week_no', '>=', $start_year_week_no)
                ->where('week_no', '<=', $end_year_week_no)
                ->where('approved', 0)
                ->get();

            if ($report == 'week') {
                $week_nos = range($start_week_no, $end_week_no);
                $report_type = 'per_week';
            } elseif ($report == 'project') {
                $report_type = 'per_project';

                $projects_array = $week_states->map(function ($week_state) {
                    return $week_state->projects()->pluck('staffing_projects.id')->unique()->toArray();
                })->flatten();

                $projects = StaffingProject::with('weekStates')->whereIn('id', $projects_array)->get();
            }

            $pdf = PDF::loadView('StaffingCompany.WeekState.week_states_report', compact('week_nos', 'projects', 'report_type', 'week_states', 'year'));
        }

        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('WeekstaatReport.pdf');
    }
}
