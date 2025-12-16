<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\StaffingCompany\Department;
use App\StaffingCompany\DepartmentDocument;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // $departments = Department::all()->sortByDesc('created_at');
        $departments = Department::withCount(['staffingProjects', 'customer'])
            ->with(['staffingProjects', 'customer'])
            ->orderByDesc('created_at')
            ->get();
        return view('StaffingCompany.Department.index')->with('departments', $departments);
    }

    public function create()
    {
        $translations = __('Staffing_Company/Department/crud');
        $customers = Customer::where('company_id', _user()->company_id)->latest()->get();
        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->id,
                'name' => $customer->name,
            ];
        }

        $customers = $data;

        return view('StaffingCompany.Department.create', compact('customers', 'translations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // dd($request->all());

        $department = new Department();
        $department->customer_id = $request->selected;
        $department->name = $request->name;
        $department->address = $request->address;
        $department->postcode = $request->post_code;
        $department->city = $request->city;
        $department->mailbox = $request->mail_box;
        $department->postal_code = $request->po_box;
        $department->po_box_city = $request->po_box_city;
        $department->phone = _formatPhoneNumber($request->phone);
        $department->email = $request->email;
        $department->fax = $request->fax;
        $department->notes = $request->notes;
        $department->save();

        if ($request->has('document_logs')) {
            foreach ($request->document_logs as $document) {
                if (isset($document['file']) && $document['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('department_docs', $fileName, 'public');

                    $department->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }
        return response()->json([
            'message' => __('Staffing_Company/Department/crud.department_create')
        ]);
    }

    public function show($id)
    {
        return response()->json(Department::with('customer')->find($id));
    }

    // public function edit($id)
    // {
    //     $translations = __('Staffing_Company/Department/crud');
    //     return view('StaffingCompany.Department.update',compact('id','translations'));
    // }
    public function edit($id)
    {


        $department = Department::with('documents')->find($id);

        if (!$department) {
            return response()->json(['error' => 'Department not found'], 404);
        }

        // Modify document paths
        $department->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/department_docs/' . $document->file);
            return $document;
        });

        // Debugging to check updated department data

        $translations = __('Staffing_Company/Department/crud');

        return view('StaffingCompany.Department.update', compact('id', 'translations', 'department'));
    }

    public function view($id)
    {
        $translations = __('Staffing_Company/Department/crud');
        $department = Department::with('documents')->find($id);
        return view('StaffingCompany.Department.show', compact('id', 'translations', 'department'));
    }

    public function update(Request $request, $id)
    {

        Department::where('id', $id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'postcode' => $request->post_code,
            'city' => $request->city,
            'mailbox' => $request->mailbox,
            'postal_code' => $request->postal_code,
            'po_box_city' => $request->po_box_city,
            'phone' => _formatPhoneNumber($request->phone),
            'email' => $request->email,
            'fax' => $request->fax,
            'notes' => $request->notes,
        ]);
        if ($request->document_logs) {
            foreach ($request->document_logs as $document) {

                if ($document['file']) {

                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();

                    $file->storeAs('customer_docs', $fileName, 'public');

                    $request->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }

        if ($request->removedDocumentIds) {
            DepartmentDocument::whereIn('id', $request->removedDocumentIds)->delete();
        }

        return response()->json([
            'message' => __('Staffing_Company/Department/crud.department_updated')
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     Department::where('id',$id)->update([
    //         'name' => $request->name,
    //         'address' => $request->address,
    //         'postcode' => $request->post_code,
    //         'city' => $request->city,
    //         'mailbox' => $request->mailbox,
    //         'postal_code' => $request->postal_code,
    //         'po_box_city' => $request->po_box_city,
    //         'phone' => _formatPhoneNumber($request->phone),
    //         'email' => $request->email,
    //         'fax' => $request->fax,
    //         'notes' => $request->notes,
    //     ]);
    // }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->back()->with('message', 'Department Deleted Successfully!');
    }

    public function getDepartmentsForCustomer(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $departments = $customer->departments;

        return response()->json($departments);
    }
}
