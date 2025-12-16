<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Role;
use App\Models\StaffingCompany\Contact;
use App\Models\StaffingCompany\Department;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContactController extends Controller
{
   public function index()
    {
        $contacts = Contact::with(['departments', 'staffingProjects'])
            ->withCount('staffingprojects')
            ->latest()
            ->get();
        // $contacts = Contact::with('department')->latest()->get();
        return view('StaffingCompany.Contacts.index')->with('contacts', $contacts);
    }


    public function create()
    {
        $departments = Department::latest()->get();
        $translations = __('Staffing_Company/common');

        return view('StaffingCompany.Contacts.create', compact('departments', 'translations'));
    }

    // public function store(ContactRequest $request)
    // {
    //     $existingUser = User::where('email', '=', $request->email)->first();

    //     if ($existingUser) {
    //         return response()->json([
    //             'error' => __('Staffing_Company/Contact/crud.email_already_taken')
    //         ], 422);
    //     }

    //     // Create User if first_name and email provided
    //     $user_id = null;

    //     if ($request->email && $request->first_name) {
    //         $role_id = Role::where('name', '=', 'user')->pluck('id')->first();

    //         $user = new User();
    //         $user->role_id = $role_id;
    //         $user->email = $request->email;
    //         $user->name = $request->first_name;
    //         $user->company_id = _user()->company_id;
    //         $user->password = Hash::make($request->password);
    //         $user->slug = Str::slug($request->first_name) . '-' . time();
    //         $user->save();

    //         $user_id = $user->id;
    //     }

    //     // Create Contact
    //     $contact = new Contact();
    //     $contact->user_id = $user_id;
    //     $contact->department_id = $request->department;
    //     $contact->first_name = $request->first_name;
    //     $contact->last_name = $request->last_name;
    //     $contact->function_text = $request->function_text;
    //     $contact->active = $request->active;
    //     $contact->dates = $request->dates;
    //     $contact->telephone = _formatPhoneNumber($request->telephone);
    //     $contact->private_phone = _formatPhoneNumber($request->private_phone);
    //     $contact->mobile = $request->mobile;
    //     $contact->notes = $request->notes;
    //     $contact->fax = $request->fax;
    //     $contact->email = $request->email;
    //     $contact->password = Hash::make($request->password);
    //     $contact->save();

    //     // Return success message from lang file
    //     return response()->json([
    //         'status' => true,
    //         'message' => __('Staffing_Company/Contact/crud.contact_create')
    //     ]);
    // }
    public function store(ContactRequest $request)
    {
        $existingUser = User::where('email', '=', $request->email)->first();

        if ($existingUser) {
            return response()->json([
                'error' => __('Staffing_Company/Contact/crud.email_already_taken')
            ], 422);
        }

        $user_id = null;

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

        $contact = new Contact();
        $contact->user_id = $user_id;
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->function_text = $request->function_text;
        $contact->active = $request->active;
        $contact->dates = $request->dates;
        $contact->telephone = _formatPhoneNumber($request->telephone);
        $contact->private_phone = _formatPhoneNumber($request->private_phone);
        $contact->mobile = $request->mobile;
        $contact->notes = $request->notes;
        $contact->fax = $request->fax;
        $contact->email = $request->email;
        $contact->password = Hash::make($request->password);
        $contact->save();

        if ($request->has('departments') && is_array($request->departments)) {
            $contact->departments()->sync($request->departments);
        }
        return response()->json([
            'status' => true,
            'message' => __('Staffing_Company/Contact/crud.contact_create')
        ]);
    }



    public function show($id)
    {
        return response()->json(Contact::with('departments')->find($id));
    }

    public function view($id)
    {
        $translations = __('Staffing_Company/common');
        $contact = Contact::with('departments')->findOrFail($id);
        return view('StaffingCompany.Contacts.show', compact('contact', 'translations'));
    }

    public function edit($id)
    {
        $contact = Contact::with('departments')->findOrFail($id);
        $departments = Department::latest()->get();
        $translations = __('Staffing_Company/common');
        return view('StaffingCompany.Contacts.update', compact('contact', 'translations', 'departments'));
    }

    // public function update(Request $request, $id)
    // {

    //     $contact = Contact::findOrFail($id);

    //     if ($contact->user_id) {
    //         if ($request->password) {
    //             User::where('id', $contact->user_id)->update([
    //                 'password' => Hash::make($request->password)
    //             ]);
    //         }
    //         if ($request->email) {

    //             User::where('id', $contact->user_id)->update([
    //                 'email' => $request->email
    //             ]);
    //         }

    //         $user_id = $contact->user_id;
    //     } else {

    //         if ($request->email && $request->first_name && $request->password) {
    //             $existingUser = User::where('email', '=', $request->email)->first();

    //             if (!$existingUser) {
    //                 $role_id = Role::where('name', '=', 'user')->pluck('id')->first();

    //                 $user = new User();

    //                 $user->role_id = $role_id;
    //                 $user->email = $request->email;
    //                 $user->company_id = _user()->company_id;
    //                 $user->name = $request->first_name;
    //                 $user->password = Hash::make($request->password);
    //                 $user->slug = Str::slug($request->first_name) . '-' . time();
    //                 $user->save();

    //                 $user_id = $user->id;
    //             } else {
    //                 return response()->json([
    //                     'error' => 'Update Unsuccessful. This email is being used by another user. Please Use another email.'
    //                 ], 422);
    //             }
    //         }
    //     }

    //     Contact::where('id', $id)->update([
    //         'user_id' => $user_id ?: null,
    //         'department_id' => $request->department,
    //         'initials' => $request->initials,
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'function_text' => $request->function_text,
    //         'active' => $request->active,
    //         'dates' => $request->dates,
    //         'telephone' => _formatPhoneNumber($request->telephone),
    //         'notes' => $request->notes,
    //         'private_phone' => $request->private_phone,
    //         'mobile' =>  $request->mobile,
    //         'mobile1' => _formatPhoneNumber($request->mobile1),
    //         'fax' => $request->fax,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     return response()->json([
    //         'status' => true,
    //         'message' => __('Staffing_Company/Contact/crud.contact_update')
    //     ]);
    // }

    public function update(Request $request, $id)
    {

        $contact = Contact::findOrFail($id);

        if ($contact->user_id) {
            if ($request->password) {
                User::where('id', $contact->user_id)->update([
                    'password' => Hash::make($request->password)
                ]);
            }
            if ($request->email) {

                User::where('id', $contact->user_id)->update([
                    'email' => $request->email
                ]);
            }

            $user_id = $contact->user_id;
        } else {

            if ($request->email && $request->first_name && $request->password) {
                $existingUser = User::where('email', '=', $request->email)->first();

                if (!$existingUser) {
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
                } else {
                    return response()->json([
                        'error' => 'Update Unsuccessful. This email is being used by another user. Please Use another email.'
                    ], 422);
                }
            }
        }

        Contact::where('id', $id)->update([
            'user_id' => $user_id ?: null,
            //'department_id' => $request->department,
            'initials' => $request->initials,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'function_text' => $request->function_text,
            'active' => $request->active,
            'dates' => $request->dates,
            'telephone' => _formatPhoneNumber($request->telephone),
            'notes' => $request->notes,
            'private_phone' => $request->private_phone,
            'mobile' =>  $request->mobile,
            'mobile1' => _formatPhoneNumber($request->mobile1),
            'fax' => $request->fax,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $contact->departments()->sync($request->departments ?? []);
        return response()->json([
            'status' => true,
            'message' => __('Staffing_Company/Contact/crud.contact_update')
        ]);
    }

    public function destroy(Contact $contact)
    {

        $contact->delete();

        return redirect()->back()->with('message', 'Contact Deleted Successfully!');
    }

    public function getContactsForDepartment(Request $request)
    {
        $department = Department::findOrFail($request->department_id);
        $contacts = $department->contacts()->where('active', 1)->get();

        return response()->json($contacts);
    }
}
