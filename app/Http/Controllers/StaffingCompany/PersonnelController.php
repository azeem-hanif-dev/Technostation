<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonnelRequest;
use App\Models\EmployAgency;
use App\Models\StaffingCompany\StaffType;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\StaffingCompany\EmployeeFunction;
use App\Models\StaffingCompany\Nationality;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\PersonnelDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnels = Personnel::latest('id')->get();
        return view('StaffingCompany.Personnel.index')->with('personnels', $personnels);
    }

    public function create()
    {
        $user = _user();

        $agencies = EmployAgency::where('company_id', $user->company_id)->orderBy('name')->get();
        $nationalities = Nationality::orderBy('name')->get();
        $e_functions = EmployeeFunction::orderBy('name')->get();
        $staff_types = StaffType::all();
        $translations = __('Staffing_Company/staff/create');
        $common = __('Staffing_Company/common');

        return view(
            'StaffingCompany.Personnel.create',
            compact('nationalities', 'agencies', 'staff_types', 'e_functions', 'translations', 'common')
        );
    }

    public function store(PersonnelRequest $request)
    {
        $user_id = null;

        $vca = 0;
        $own_car = 0;
        $active = 0;

        if ($request->vca_certificate) {
            $vca = 1;
        }

        if ($request->own_car) {
            $own_car = 1;
        }

        if ($request->active) {
            $active = 1;
        }

        try {
            DB::beginTransaction();

            if ($request->email && $request->first_name) {

                $role_id = Role::where('name', '=', 'user')->pluck('id')->first();

                $user = new User();

                $user->role_id = $role_id;
                $user->email = $request->email;
                $user->name = $request->first_name;
                $user->company_id = _user()->company_id;
                $user->password = Hash::make($request->password);
                $user->slug = Str::slug($request->first_name) . '-' . time();
                $user->save();

                $user_id = $user->id;
            }

            $personnel = new Personnel();

            $personnel->user_id = $user_id;
            $personnel->first_name = $request->first_name;
            $personnel->last_name = $request->last_name;
            $personnel->employ_agency_id = $request->employment_agency;
            $personnel->staff_type_id = $request->staff_type_id;
            $personnel->function_id = $request->function_id;
            $personnel->salutation = $request->salutation;
            $personnel->gender = $request->gender;
            $personnel->initials = $request->initials;
            $personnel->dob = Carbon::parse($request->dob)->format('Y-m-d');
            $personnel->social_security_number = $request->social_security_number;
            $personnel->date_service = Carbon::parse($request->date_service)->format('Y-m-d');
            $personnel->telephone = $request->telephone;
            $personnel->mobile = _formatPhoneNumber($request->mobile);
            $personnel->mobile2 = $request->mobile2;
            $personnel->mobile3 = $request->mobile3;
            $personnel->email = $request->email;
            $personnel->password = Hash::make($request->password);
            $personnel->vca_certificate = $vca;
            $personnel->vca_number = $request->vca_number;
            $personnel->vca_expiry = $request->vca_expiry;
            $personnel->own_car = $own_car;
            $personnel->id_type = $request->id_type;
            $personnel->id_number = $request->id_number;
            $personnel->id_expiry = Carbon::parse($request->expiration_date)->format('Y-m-d');
            $personnel->nationality = $request->nationality;
            $personnel->address = $request->address;
            $personnel->postcode = $request->postcode;
            $personnel->city = $request->city;
            $personnel->active = $active;
            $personnel->employment_agency_note = $request->employment_agency_note;
            $personnel->rate_per_hour = $request->rate_per_hour;
            $personnel->cost_per_hour = $request->cost_per_hour;
            $personnel->personnel_dates = $request->dates;

            $personnel->save();

            $personnel->employeeFunction()->sync($request->selectedOptions);

            if ($request->has('picture')) {
                $file = $request->file('picture');

                if ($file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('personnel_pictures', $fileName, 'public');
                    Personnel::where('id', $personnel->id)->update([
                        'picture' => $fileName
                    ]);
                }
            }

            if ($request->personnel_logs) {
                foreach ($request->personnel_logs as $document) {
                    if ($document['file']) {

                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();

                        $file->storeAs('personnel_docs', $fileName, 'public');

                        $personnel->documents()->create([
                            'type' => $document['doc_type'],
                            'expiry_date' => $document['expiry'],
                            'file' => $fileName,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => __('Staffing_Company/staff/create.create_personnel')
            ]);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->sendError(__('messages.invalid_input'));
        }
    }
    public function show($id)
    {
        return response()->json(Personnel::with(['agency', 'employeeFunction'])->where('id', $id)->first());
    }

    public function view($id)
    {

        $personnel = Personnel::find($id);
        $personnel->telephone = $personnel->telephone;
        $personnel->mobile = _formatPhoneNumber($personnel->mobile);
        $personnel->mobile2 = $personnel->mobile2;
        $personnel->mobile3 = $personnel->mobile3;
        return view('StaffingCompany.Personnel.show', compact('personnel'));
    }

    public function edit($id)
    {
        $user = _user();

        $translations = __('Staffing_Company/staff/create');
        $token = _user()->fcm_token;
        $personnel = Personnel::with('documents')->find($id);

        $personnel->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/personnel_docs/' . $document->file);
            return $document;
        });

        $personnel_functions = $personnel->employeeFunction->pluck('id')->unique()->toArray();
        $personnel_function_names = $personnel->employeeFunction->pluck('name')->unique()->toArray();
        $agencies = EmployAgency::where('company_id', $user->company_id)->orderBy('name')->get();
        $nationalities = Nationality::orderBy('name')->get();
        $e_functions = EmployeeFunction::orderBy('name')->get();
        $staff_types = StaffType::all();
        $common = __('Staffing_Company/common');

        return view('StaffingCompany.Personnel.update', compact('id', 'common', 'personnel_function_names', 'common', 'staff_types', 'personnel_functions', 'token', 'personnel', 'nationalities', 'translations', 'agencies', 'e_functions'));
    }
    public function update(Request $request, $id)
    {
        $personnel = Personnel::find($id);
        $user_id = null;

        try {
            DB::beginTransaction();

            if ($personnel->user_id) {
                if ($request->password) {
                    User::where('id', $personnel->user_id)->update([
                        'password' => Hash::make($request->password)
                    ]);
                }
                if ($request->email) {
                    User::where('id', $personnel->user_id)->update([
                        'email' => $request->email
                    ]);
                }

                $user_id = $personnel->user_id;
            } else {
                if ($request->email && $request->first_name && $request->password) {
                    $role_id = Role::where('name', '=', 'user')->pluck('id')->first();

                    $user = new User();
                    $user->role_id = $role_id;
                    $user->email = $request->email;
                    $user->company_id = _user()->company_id;
                    $user->name = $request->first_name;
                    $user->password = Hash::make($request->password);
                    $user->slug = Str::slug($request->first_name) . '-' . time();
                    $user->save();

                    $user_id = $user->id;
                }
            }

            Personnel::where('id', $id)->update([
                'user_id' => $user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'employ_agency_id' => $request->employment_agency,
                'staff_type_id' => $request->staff_type_id ?? 1,
                'function_id' => $request->function_id,
                'salutation' => $request->salutation,
                'initials' => $request->initials,
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'social_security_number' => $request->social_security_number,
                'date_service' => Carbon::parse($request->date_service)->format('Y-m-d'),
                'telephone' => $request->telephone,
                'mobile' => _formatPhoneNumber($request->mobile),
                'mobile2' => $request->mobile2,
                'mobile3' => $request->mobile3,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'vca_certificate' => $request->vca_certificate,
                'vca_number' => $request->vca_number,
                'vca_expiry' => $request->vca_expiry,
                'own_car' => $request->own_car,
                'id_type' => $request->id_type,
                'id_number' => $request->id_number,
                'id_expiry' => Carbon::parse($request->expiration_date)->format('Y-m-d'),
                'nationality' => $request->nationality,
                'address' => $request->address,
                'postcode' => $request->postcode,
                'city' => $request->city,
                'active' => $request->active,
                'employment_agency_note' => $request->employment_agency_note,
                'rate_per_hour' => $request->rate_per_hour,
                'cost_per_hour' => $request->cost_per_hour,
                'personnel_dates' => $request->dates
            ]);

            $personnel->employeeFunction()->sync($request->selectedOptions);

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');

                if ($file) {
                    Storage::disk('public')->delete('personnel_pictures/' . $personnel->picture);
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('personnel_pictures', $fileName, 'public');
                    Personnel::where('id', $personnel->id)->update([
                        'picture' => $fileName
                    ]);
                }
            }

            if ($request->personnel_logs) {
                foreach ($request->personnel_logs as $document) {
                    if ($document['file']) {
                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('personnel_docs', $fileName, 'public');

                        $personnel->documents()->create([
                            'type' => $document['doc_type'],
                            'expiry_date' => $document['expiry'],
                            'file' => $fileName,
                        ]);
                    }
                }
            }

            if ($request->personnel_update_logs) {
                foreach ($request->personnel_update_logs as $log) {
                    if ($log['id']) {
                        $personnel->documents()->where('id', $log['id'])->update([
                            'type' => $log['doc_type'],
                            'expiry_date' => $log['expiry'],
                        ]);
                    }
                }
            }

            if ($request->removedDocumentIds) {
                PersonnelDocument::whereIn('id', $request->removedDocumentIds)->delete();
            }

            DB::commit();

            // ✅ Send credentials only after DB commit
            if ($request->has('send_credentials') && $request->send_credentials == true) {
                if ($request->email && $request->password) {
                    try {
                        \Mail::to($request->email)->send(new \App\Mail\SendPersonnelCredentials(
                            $request->email,
                            $request->password
                        ));


                        \Log::info('Personnel credentials sent successfully', [
                            'personnel_id' => $id,
                            'email' => $request->email,
                            'sent_by' => auth()->id(),
                            'sent_at' => now()->toDateTimeString(),
                        ]);

                        return response()->json([
                            'status' => true,
                            'message' => "Credentials sent successfully to user ({$request->email})"
                        ]);
                    } catch (\Throwable $e) {
                        // ❌ Failed log
                        \Log::error('Failed to send personnel credentials', [
                            'personnel_id' => $id,
                            'email' => $request->email,
                            'sent_by' => auth()->id(),
                            'sent_at' => now()->toDateTimeString(),
                            'error' => $e->getMessage(),
                        ]);

                        return $this->sendError("Failed to send email: " . $e->getMessage());
                    }
                }
            }

            // ✅ Default return
            return response()->json([
                'status' => true,
                'message' => __('Staffing_Company/staff/create.update_personnel')
            ]);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->sendError($throwable->getMessage());
        }
    }

    public function destroy(Personnel $personnel)
    {
        $personnel->delete();

        return redirect()->back()->with('message', 'Personnel Deleted Successfully!');
    }

    public function employeeHistory(Personnel $personnel)
    {
        return view('StaffingCompany.Personnel.history', compact('personnel'));
    }

    public function employeeSearchWorkHistory(Request $request, Personnel $personnel)
    {

        $from_week_no = $request->from_year . $request->from_week;
        $to_week_no = $request->to_year . $request->to_week;

        $plannings = $personnel->planning()
            ->where('week_no', '>=', $from_week_no)
            ->where('week_no', '<=', $to_week_no)
            ->get();
        $plannings = $plannings->load('employeeFunction');

        foreach ($plannings as $planning) {
            $project = _getProjectNameById($planning->project_id);
            $planning->project_name = $project->name;
            $week_state = $project->weekStates()->where('week_no', $planning->week_no)->first();

            if (!$week_state) {
                continue;
            }

            $week_card = $week_state->weekCards()
                ->where('personnel_id', $personnel->id)
                ->where('week_no', $planning->week_no)
                ->first();
            $planning->week_card = $week_card;
        }

        return view('StaffingCompany.Personnel.history', [
            'personnel' => $personnel,
            'plannings' => $plannings,
            'from_week_no' => $from_week_no,
            'to_week_no' => $to_week_no,
            'selected_from_year' => $request->from_year ?? now()->year,
            'selected_from_week' => $request->from_week ?? now()->weekOfYear,
            'selected_to_year' => $request->to_year ?? now()->year,
            'selected_to_week' => $request->to_week ?? now()->weekOfYear,
        ]);
    }

    public function employeeWorkHistoryPDF(Personnel $personnel, $from_week_no, $to_week_no)
    {

        $plannings = $personnel->planning()
            ->where('week_no', '>=', $from_week_no)
            ->where('week_no', '<=', $to_week_no)
            ->get();

        $employee_pdf_name = $personnel->first_name . '_' . $personnel->last_name . '_Work_History.pdf';

        foreach ($plannings as $planning) {
            $project = _getProjectNameById($planning->project_id);
            $planning->project_name = $project->name;
            $week_state = $project->weekStates()->where('week_no', $planning->week_no)->first();

            if (!$week_state) {
                continue;
            }

            $week_card = $week_state->weekCards()
                ->where('personnel_id', $personnel->id)
                ->where('week_no', $planning->week_no)
                ->first();
            $planning->week_card = $week_card;
        }

        $pdf = PDF::loadView('StaffingCompany.Personnel.pdf-work-history', compact('personnel', 'plannings', 'from_week_no', 'to_week_no'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download($employee_pdf_name);
    }


    public function bulk_update_password(Request $request)
    {
        //dd($request->ids);
        try {
            DB::beginTransaction();
            $ids = $request->ids;

            if (!$ids || !is_array($ids)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No personnel selected.'
                ], 400);
            }

            foreach ($ids as $id) {

                $personnel = Personnel::find($id);

                if (!$personnel || !$personnel->email) {
                    continue;
                }
                $plainPassword = Str::random(8);
                $hashedPassword = Hash::make($plainPassword);
                $personnel->password = $hashedPassword;
                $personnel->save();
                if ($personnel->user_id) {
                    User::where('id', $personnel->user_id)->update([
                        'password' => $hashedPassword
                    ]);
                }
                try {
                    SendPersonnelEmail::dispatch($personnel->email, $plainPassword);
                } catch (\Throwable $e) {
                    \Log::error("Failed to send bulk password mail", [
                        'personnel_id' => $personnel->id,
                        'email' => $personnel->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Passwords updated and emails sent successfully.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
