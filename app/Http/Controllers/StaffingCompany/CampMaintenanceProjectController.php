<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\StaffingCompany\StaffingProjectDocument;
use App\Models\StaffingCompany\{
    CampMaintenanceProject,
    CampMaintenanceWeekState,
    CampProjectDocument,
    StaffingProject,
    Personnel
};
use PDF;

class CampMaintenanceProjectController extends Controller
{

    public function index()
    {
        $projects = CampMaintenanceProject::with(['project.customer', 'project.department'])
            ->latest()
            ->get();

        $translations = __('Staffing_Company/Camp_Maintenance/index');

        return view('StaffingCompany.Camp_Maintenance.index', compact('projects', 'translations'));
    }

    public function create()
    {
        $projects = StaffingProject::with(['customer', 'department'])->select('id', 'name')->get();
        $personnels = Personnel::select('id', 'first_name', 'last_name')->get();
        $translations = __('Staffing_Company/Camp_Maintenance/create');

        return view('StaffingCompany.Camp_Maintenance.create', compact('projects', 'personnels', 'translations'));
    }

    public function store(Request $request)
    {

        //Log::info('Camp Maintenance Request:', $request->all());

        $request->validate([
            'project_id' => 'required|integer|exists:staffing_projects,id',
            'week_no' => 'required|string',
            'date' => 'nullable|date',
            'rows' => 'required|array|min:1',
            'rows.*.personnel_id' => 'required|integer|exists:personnels,id',
            //
            'documents' => 'nullable|array',
            'documents.*.type' => 'nullable|string',
            'documents.*.expiry_date' => 'nullable|date',
            'documents.*.file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
        ]);

        DB::beginTransaction();
        try {
            $staffing = StaffingProject::findOrFail($request->project_id);

            $camp = CampMaintenanceProject::create([
                'project_id' => $staffing->id,
                'customer_id' => $staffing->customer_id,
                'department_id' => $staffing->department_id,
                'supervisor_id' => $staffing->performer,
                'week_no' => $request->week_no,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?: null,
                'notes' => $request->notes,
                'work_type' => $request->work_type,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->rows as $row) {
                $total = collect([
                    $row['hours_mon'] ?? 0,
                    $row['hours_tue'] ?? 0,
                    $row['hours_wed'] ?? 0,
                    $row['hours_thu'] ?? 0,
                    $row['hours_fri'] ?? 0,
                    $row['hours_sat'] ?? 0,
                    $row['hours_sun'] ?? 0,
                ])->sum();

                CampMaintenanceWeekState::create([
                    'camp_project_id' => $camp->id,
                    'personnel_id' => $row['personnel_id'],
                    'supervisor_id' => $staffing->performer,
                    'hours_mon' => $row['hours_mon'] ?? 0,
                    'hours_tue' => $row['hours_tue'] ?? 0,
                    'hours_wed' => $row['hours_wed'] ?? 0,
                    'hours_thu' => $row['hours_thu'] ?? 0,
                    'hours_fri' => $row['hours_fri'] ?? 0,
                    'hours_sat' => $row['hours_sat'] ?? 0,
                    'hours_sun' => $row['hours_sun'] ?? 0,
                    'total_hours' => $total,
                ]);
            }

            // Save documents
            foreach ($request->documents ?? [] as $doc) {
                $filePath = null;

                if (isset($doc['file']) && $doc['file'] instanceof \Illuminate\Http\UploadedFile) {
                    // Save file to storage/app/public/camp_documents
                    $filePath = $doc['file']->store('camp_documents', 'public');
                }

                CampProjectDocument::create([
                    'camp_project_id' => $camp->id,
                    'type' => $doc['type'] ?? null,
                    'expiry_date' => $doc['expiry_date'] ?? null,
                    'file' => $filePath,
                ]);
            }



            // $docs = $request->documents ?? [];
            // $files = $request->file('document_files') ?? [];

            // foreach ($docs as $i => $doc) {
            //     $filePath = null;

            //     if (isset($files[$i])) {
            //         // Save file to storage/app/public/camp_documents
            //         $filePath = $files[$i]->store('camp_documents', 'public');
            //     }

            //     CampProjectDocument::create([
            //         'camp_project_id' => $camp->id,
            //         'type' => $doc['type'] ?? null,
            //         'expiry_date' => $doc['expiry_date'] ?? null,
            //         'file' => $filePath,
            //     ]);
            // }

            DB::commit();
            return response()->json(['message' => 'Camp Maintenance Project created successfully!', 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $campMaintenanceProject = CampMaintenanceProject::with([
            'project.customer',
            'project.department',
            'supervisor',
            'weekStates.personnel',
            'documents',
        ])->findOrFail($id);

        $camp = [
            'id'              => $campMaintenanceProject->id,
            'project_id'      => $campMaintenanceProject->project_id,
            'customer_name'   => optional($campMaintenanceProject->project->customer)->name ?? 'N/A',
            'department_name' => optional($campMaintenanceProject->project->department)->name ?? 'N/A',
            'supervisor_name' => optional($campMaintenanceProject->supervisor)->first_name
                . ' ' .
                optional($campMaintenanceProject->supervisor)->last_name ?? 'N/A',
            'week_no'         => $campMaintenanceProject->week_no,
            'start_date'            => $campMaintenanceProject->start_date,
            'end_date'            => $campMaintenanceProject->end_date,
            'work_type'       => $campMaintenanceProject->work_type ?? 'Morning',
            'notes'           => $campMaintenanceProject->notes,
            'week_states'     => $campMaintenanceProject->weekStates,
            'documents' => $campMaintenanceProject->documents
                ->sortBy('id')
                ->map(function ($d) {
                    return [
                        'id' => $d->id,
                        'type' => $d->type ?: 'N/A',
                        'expiry_date' => $d->expiry_date ?: 'N/A',
                        'file' => $d->file ?: null,
                    ];
                })
                ->values(),
        ];

        // $projects = StaffingProject::with(['customer', 'department'])->get();
        // $personnels = Personnel::select('id', 'first_name', 'last_name')->get();
        // Changed this line to include projects and personnels
        // return view('StaffingCompany.CampMaintenance.show', compact('projects', 'personnels', 'camp'));

        return view('StaffingCompany.Camp_Maintenance.show', compact('camp'));
    }

    public function edit($id)
    {
        $campMaintenanceProject = CampMaintenanceProject::with([
            'project.customer',
            'project.department',
            'supervisor',
            'weekStates',
            'documents'
        ])->findOrFail($id);

        $projects = StaffingProject::with(['customer', 'department'])->get();
        $personnels = Personnel::select('id', 'first_name', 'last_name')->get();
        $translations = __('Staffing_Company/Camp_Maintenance/create');

        $camp = [
            'id' => $campMaintenanceProject->id,
            'project_id' => $campMaintenanceProject->project_id,
            'customer_name' => optional(optional($campMaintenanceProject->project)->customer)->name ?? '',
            'department_name' => optional(optional($campMaintenanceProject->project)->department)->name ?? '',
            'supervisor_name' => $campMaintenanceProject->supervisor
                ? ($campMaintenanceProject->supervisor->first_name . ' ' . $campMaintenanceProject->supervisor->last_name)
                : 'N/A',
            // 'supervisor_name' => optional($campMaintenanceProject->supervisor)->first_name ?? '',
            'week_no' => $campMaintenanceProject->week_no,
            'start_date' => $campMaintenanceProject->start_date,
            'end_date' => $campMaintenanceProject->end_date,
            'work_type' => $campMaintenanceProject->work_type ?? 'Morning',
            'notes' => $campMaintenanceProject->notes,
            'week_states' => $campMaintenanceProject->weekStates,
            'documents' => $campMaintenanceProject->documents ?? [],
        ];

        return view('StaffingCompany.Camp_Maintenance.update', compact('projects', 'personnels', 'camp', 'translations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:staffing_projects,id',
            'week_no' => 'required|string',
            'rows' => 'required|array|min:1',
            'rows.*.personnel_id' => 'required|integer|exists:personnels,id',
        ]);

        DB::beginTransaction();
        try {
            $campMaintenanceProject = CampMaintenanceProject::findOrFail($id);

            // Get the staffing project details
            $staffing = StaffingProject::findOrFail($request->project_id);

            $campMaintenanceProject->update([
                'project_id' => $request->project_id,
                'customer_id' => $staffing->customer_id,
                'department_id' => $staffing->department_id,
                'supervisor_id' => $staffing->performer,
                'week_no' => $request->week_no,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'work_type' => $request->work_type,
                'notes' => $request->notes,
            ]);

            // Delete & reinsert week states
            CampMaintenanceWeekState::where('camp_project_id', $campMaintenanceProject->id)->delete();

            foreach ($request->rows as $row) {
                $total = collect([
                    isset($row['hours_mon']) ? $row['hours_mon'] : 0,
                    isset($row['hours_tue']) ? $row['hours_tue'] : 0,
                    isset($row['hours_wed']) ? $row['hours_wed'] : 0,
                    isset($row['hours_thu']) ? $row['hours_thu'] : 0,
                    isset($row['hours_fri']) ? $row['hours_fri'] : 0,
                    isset($row['hours_sat']) ? $row['hours_sat'] : 0,
                    isset($row['hours_sun']) ? $row['hours_sun'] : 0,
                ])->sum();

                CampMaintenanceWeekState::create([
                    'camp_project_id' => $campMaintenanceProject->id,
                    'personnel_id' => $row['personnel_id'],
                    'supervisor_id' => $staffing->performer,
                    'hours_mon' => isset($row['hours_mon']) ? $row['hours_mon'] : 0,
                    'hours_tue' => isset($row['hours_tue']) ? $row['hours_tue'] : 0,
                    'hours_wed' => isset($row['hours_wed']) ? $row['hours_wed'] : 0,
                    'hours_thu' => isset($row['hours_thu']) ? $row['hours_thu'] : 0,
                    'hours_fri' => isset($row['hours_fri']) ? $row['hours_fri'] : 0,
                    'hours_sat' => isset($row['hours_sat']) ? $row['hours_sat'] : 0,
                    'hours_sun' => isset($row['hours_sun']) ? $row['hours_sun'] : 0,
                    'total_hours' => $total,
                ]);
            }


            // Handle documents
            $existingDocIds = [];
            if ($request->has('documents')) {
                foreach ($request->documents as $doc) {
                    // Existing document
                    if (!empty($doc['id'])) {
                        $existingDocIds[] = $doc['id'];
                        $document = $campMaintenanceProject->documents()->find($doc['id']);
                        if ($document) {
                            $document->update([
                                'type' => $doc['type'] ?? $document->type,
                                'expiry_date' => $doc['expiry_date'] ?? $document->expiry_date,
                            ]);
                            if (!empty($doc['file'])) {
                                if ($document->file && Storage::disk('public')->exists($document->file)) {
                                    Storage::disk('public')->delete($document->file);
                                }
                                $document->update([
                                    'file' => $doc['file']->store('camp_documents', 'public')
                                ]);
                            }
                        }
                    }
                    // New document
                    else if (!empty($doc['file'])) {
                        $newDoc = $campMaintenanceProject->documents()->create([
                            'type' => $doc['type'] ?? '',
                            'expiry_date' => $doc['expiry_date'] ?? null,
                            'file' => $doc['file']->store('camp_documents', 'public')
                        ]);

                        // Add the new doc ID to existingDocIds to prevent deletion
                        $existingDocIds[] = $newDoc->id;
                    }
                }
            }

            // Delete removed documents
            $campMaintenanceProject->documents()
                ->whereNotIn('id', $existingDocIds)
                ->get()
                ->each(function ($doc) {
                    if ($doc->file && Storage::disk('public')->exists($doc->file)) {
                        Storage::disk('public')->delete($doc->file);
                    }
                    $doc->delete();
                });


            DB::commit();
            return response()->json(['message' => 'Project updated successfully!', 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function downloadPdf($id)
    {
        $campMaintenanceProject = CampMaintenanceProject::with([
            'project',
            'customer',
            'department',
            'supervisor',
            'weekStates.personnel',
        ])->findOrFail($id);

        $data = [
            'camp' => $campMaintenanceProject,
            'project' => $campMaintenanceProject->project,
            'customer' => $campMaintenanceProject->customer,
            'department' => $campMaintenanceProject->department,
            'supervisor' => $campMaintenanceProject->supervisor,
            'weekStates' => $campMaintenanceProject->weekStates,
        ];


        $pdf = PDF::loadView('StaffingCompany.Camp_Maintenance.pdf', $data);

        $filename = 'camp_maintenance_' . ($campMaintenanceProject->week_no ?? 'unknown') . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function destroy($id)
    {
        $camp = CampMaintenanceProject::find($id);
        $camp->delete();

        return redirect()
            ->route('camp-maintenance.index')
            ->with([
                'message' => 'Project deleted successfully!',
                'status' => 200
            ]);
    }
}
