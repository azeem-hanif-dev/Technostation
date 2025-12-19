<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Customer;
use App\Models\StaffingCompany\PersonnelDocument;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\StaffingCompany\StaffingProjectDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectCreatedMail;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\Facades\DataTables;


class StaffingProjectController extends Controller
{
    public function index()
    {
        return view('StaffingCompany.project.index');

        $projects = StaffingProject::with('department')->latest('id')->get();
        return view('StaffingCompany.project.index')->with('projects', $projects);
    }


    public function getProjects()
{
    return DataTables::of(StaffingProject::with('department'))
        ->addColumn('status', function ($project) {
            return $project->active
                ? "<span style='color:white;padding:3px;font-size:12px;background:green'>Active</span>"
                : "<span style='color:white;padding:3px;font-size:12px;background:red'>Inactive</span>";
        })
        ->rawColumns(['status'])
        ->make(true);
}


    public function create()
    {
        $translations = __('Staffing_Company/Project/crud');
        $common = __('Staffing_Company/common');
        $customers = Customer::where('company_id', _user()->company_id)->latest()->get();
        return view('StaffingCompany.project.create', compact('customers', 'common', 'translations'));
    }

    public function store(ProjectRequest $request)
    {
        //$start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        //$end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $start_date = ($request->start_date && $request->start_date !== 'null') ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $end_date = ($request->end_date && $request->end_date !== 'null') ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $existingProject = StaffingProject::where('name', $request->name)->first();
        if ($existingProject) {
            return $this->sendError('A project with this name already exists.(Project might be created.Check Project List.)');
        }

        try {
            DB::beginTransaction();

            $active = $request->active ? 1 : 0;

            $project = new StaffingProject();

            $project->customer_id = $request->customer;
            $project->department_id = $request->department;
            $project->performer = $request->performer;
            $project->name = $request->name;
            $project->start_date = $start_date;
            $project->end_date = $end_date;
            $project->project_manager = $request->project_manager;
            $project->description = $request->description;
            $project->fixed_price = $request->fixed_price;
            $project->edu_project_no = $request->ecu_project_no;
            $project->client_project_no = $request->client_project_no;
            $project->address = $request->address;
            $project->post_code = $request->post_code ?: 'postcode';
            $project->city = $request->city ?: 'city';
            // $project->lat = $request->lat;
            // $project->long = $request->long;
            $project->weekly_statement = $request->weekly_statement;
            $project->price_agreement = $request->price_agreement;
            $project->no_of_times_per_week = $request->number_of_time_per_week;
            $project->unit = $request->unit;
            $project->no_of_chain = $request->number_of_chain;
            $project->price = $request->price;
            $project->purchase_price = $request->purchase_price;
            $project->approval = $request->approval;
            $project->notes = $request->notes;
            $project->more_notes = $request->more_notes;
            $project->active = $active;

            $project->save();

            if ($request->document_logs) {
                foreach ($request->document_logs as $document) {
                    if ($document['file']) {

                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();

                        $file->storeAs('document_docs', $fileName, 'public');

                        $project->documents()->create([
                            'type' => $document['doc_type'],
                            'expiry_date' => $document['expiry'],
                            'file' => $fileName,
                        ]);
                    }
                }
            }
            Config::set('mail.from.address', 'noreply@digitalcleansolution.com');
            //Config::set('mail.from.address', 'noreply@digitalcleansolution.com');
            // Config::set('mail.from.address', 'noreply@technostation.org');
            Config::set('mail.from.name', 'Digital Clean Solution');

            // Send email
            try {
                $project_obj = StaffingProject::with([
                    'customer',
                    'department',
                    'projectPerformer',
                ])->find($project->id);
                Mail::to('info@easycleanup.nl')->send(new ProjectCreatedMail($project));
                \Log::info('✅ Mail sent successfully to info@easycleanup.nl');
                // Mail::to('noreply@technostation.org')->send(new ProjectCreatedMail($project));
                //                Mail::to('engqasimrafique@gmailcom')->send(new ProjectCreatedMail($project));
            } catch (\Exception $e) {
                \Log::info('Error sending email: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json([
                'message' => __('Staffing_Company/Project/crud.project_create')
            ]);
            // return $this->sendResponse($project, 'Project added successfully');
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->sendError($throwable->getMessage());
        }
    }
    private function sanitizeDate($date)
    {
        // This method removes the second timezone specification
        $dateParts = explode(' ', $date);
        if (count($dateParts) > 5) {
            unset($dateParts[5]); // Remove the second timezone specification
        }
        return implode(' ', $dateParts);
    }

    public function show($id)
    {
        return response()->json(StaffingProject::with(['customer', 'department', 'projectPerformer', 'documents'])->find($id));
    }

    public function edit($id)
    {
        $customers = Customer::where('company_id', _user()->company_id)->latest()->get();
        $project = StaffingProject::with(['documents', 'weekStates', 'department.contacts'])->find($id);

        $project->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/document_docs/' . $document->file);
            return $document;
        });

        $latitude = $project->lat;
        $longitude = $project->long;
        $translations = __('Staffing_Company/Project/crud');
        return view('StaffingCompany.project.update', compact('id', 'project', 'latitude', 'longitude', 'translations', 'customers'));
    }

    public function view($id = 2813)
    {
        // dd(2813);
        $translations = __('Staffing_Company/Project/crud');
        return view('StaffingCompany.project.show', compact('id', 'translations'));
    }

    public function update(ProjectRequest $request, $id)
    {
        //     $project = StaffingProject::findOrFail($id);

        //    $oldNameLength = strlen($project->notes ?? '');
        //     $request->validate([
        //         'notes' => ['required', function ($attribute, $value, $fail) use ($oldNameLength) {
        //             if (strlen($value) < $oldNameLength) {
        //                 $fail('The Notes must be at least ' . $oldNameLength . ' characters.');
        //             }
        //         }],
        //     ]);

        // Log the original dates from the request

        // $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        // $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $start_date = ($request->start_date && $request->start_date !== 'null') ? Carbon::parse($request->start_date)->format('Y-m-d') : null;
        $end_date = ($request->end_date && $request->end_date !== 'null') ? Carbon::parse($request->end_date)->format('Y-m-d') : null;

        $active = $request->active == '1' || $request->active === 'true';

        DB::beginTransaction();

        try {
            StaffingProject::where('id', $id)->update([
                'name' => $request->name,
                'department_id' => $request->department,
                'performer' => $request->performer,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'project_manager' => $request->project_manager,
                'description' => $request->description,
                'fixed_price' => $request->fixed_price,
                'edu_project_no' => $request->edu_project_no,
                'client_project_no' => $request->client_project_no,
                'address' => $request->address,
                'post_code' => $request->post_code,
                'city' => $request->city,
                'weekly_statement' => $request->weekly_statement,
                'price_agreement' => $request->price_agreement,
                'no_of_times_per_week' => $request->no_of_times_per_week,
                'unit' => $request->unit,
                'no_of_chain' => $request->no_of_chain,
                'price' => $request->price,
                'purchase_price' => $request->purchase_price,
                'approval' => $request->approval,
                'notes' => $request->notes,
                'more_notes' => $request->more_notes,
                'active' => $active,
                // 'lat' => $request->lat,
                // 'long' => $request->long,
            ]);

            $project = StaffingProject::find($id);
                if ($request->document_logs) {
                    foreach ($request->document_logs as $document) {

                        if ($document['file']) {

                            $file = $document['file'];
                            $fileName = time() . '_' . $file->getClientOriginalName();

                            $file->storeAs('document_docs', $fileName, 'public');

                            $project->documents()->create([
                                'type' => $document['doc_type'],
                                'expiry_date' => $document['expiry'] === 'null' ? NULL : $document['expiry'],
                                'file' => $fileName,
                            ]);
                        }
                    }
                }

            if ($request->document_update_logs) {
                foreach ($request->document_update_logs as $log) {
                    if ($log['id']) {
                        $project->documents()->where('id', $log['id'])->update([
                            'type' => $log['doc_type'],
                            'expiry_date' => $log['expiry'] === 'null' ? NULL : $log['expiry'],
                        ]);
                    }
                }
            }

            if ($request->removedDocumentIds) {
                StaffingProjectDocument::whereIn('id', $request->removedDocumentIds)->delete();
            }

            DB::commit();
            return response()->json([
                'message' => __('Staffing_Company/Project/crud.project_update')
            ]);
            // return response()->json([
            //     'message' => 'created',
            //     'status'  => 1
            // ], 200);
        } catch (\Throwable $throwable) {
            return response()->json([
                'message1' => $throwable->getMessage(),
                'status'  => 0
            ], 500);
        }
    }
    public function destroy(StaffingProject $staffing_project)
    {
        $messages = [];
        if ($staffing_project->weekStates()->exists()) {
            $messages[] = __('Staffing_Company/Project/p_index.has_week_states');
        }
        if ($staffing_project->projectPlannings()->exists()) {
            $messages[] = __('Staffing_Company/Project/p_index.has_project_plannings');
        }

        if (!empty($messages)) {
            $message = implode(' ', $messages);

            if (request()->ajax()) {
                return response()->json(['status' => 'has_related', 'message' => $message]);
            }

            return redirect()->back()->with('message', $message);
        }

        $staffing_project->delete();

        if (request()->ajax()) {
            return response()->json(['status' => 'deleted']);
        }

        return redirect()->back()->with('message', 'Project Deleted Successfully!');
    }
}
