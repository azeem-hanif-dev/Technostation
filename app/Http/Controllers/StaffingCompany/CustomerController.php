<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\StaffingCompany\CustomerDocument;
use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\User;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $company_id = \Auth::user()->company_id;
        // $customers = Customer::with(['company','staffingproject'])->where('company_id',$company_id)->latest('id')->get();
        $customers = Customer::withCount('staffingproject')
            ->with(['company', 'staffingproject'])
            ->where('company_id', $company_id)
            ->latest('id')
            ->get();
        return view('StaffingCompany.Customer.index')->with('customers', $customers);
    }

    public function create()
    {
        $translations = __('Staffing_Company/Customer/crud');
        return view('StaffingCompany.Customer.create', compact('translations'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|max:255',
                'address' => 'required|max:255',
                'city' => 'nullable|max:255',
                'country' => 'nullable|max:255',
                'phone' => 'nullable|max:255',
            ]);

            $user_id = null;

            if ($request->email) {
                $role_id = Role::where('name', 'user')->value('id');

                $user = new User;
                $user->role_id = $role_id;
                $user->email = $request->email;
                $user->name = $request->name ?: null;
                $user->password = $request->password
                    ? Hash::make($request->password)
                    : Hash::make('123456');
                $user->slug = Str::slug($request->name) . '-' . time();
                $formattedPhone = _formatPhoneNumber($request->phone);
                $user->phone = trim($formattedPhone) !== '' ? $formattedPhone : null;

                //$user->phone = (_formatPhoneNumber($request->phone)) ?: null;
                $user->company_id = _user()->company_id;
                $user->address = $request->address ?: null;
                $user->city = $request->city ?: null;
                $user->country = $request->country ?: null;
                $user->contact_person1 = $request->contact ?: null;
                $user->email_verified_at = null;
                $user->created_at = now();
                $user->updated_at = now();
                $user->save();

                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    $val = "Permission" . $permission->id;
                    if ($request->$val) {
                        $user->permissions()->attach((int)$request->$val);
                    }
                }

                $user_id = $user->id;
            }
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->user_id = $user_id;
            $customer->status = 1;
            $customer->company_id = auth()->user()->company_id;
            $customer->notes = $request->notes ?: null;
            $customer->slug = Str::slug($request->name) . '-' . time();
            $customer->save();

            if ($request->document_logs) {
                foreach ($request->document_logs as $document) {
                    if (!empty($document['file'])) {
                        $file = $document['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('customer_docs', $fileName, 'public');

                        $customer->documents()->create([
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
                'message' => __('Staffing_Company/Customer/crud.customer_create')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            $errors = $e->errors();
            if (isset($errors['email']) && str_contains($errors['email'][0], 'unique')) {
                $message = __('messages.email_unique');
            } else {
                $message = __('Staffing_Company/Customer/crud.required_fields');
            }

            return $this->sendError($message);
        }
        // } catch (\Throwable $throwable) {
        //     DB::rollBack();
        //     return $this->sendError(__('Staffing_Company/Customer/crud.required_fields'));
        //     // messages.email_unique
        // }
    }


    public function show($id)
    {
        return response()->json(Customer::with('user', 'documents')->find($id));
    }

    public function edit($id)
    {
        $customer = Customer::with('documents', 'user')->find($id);
        // dd($customer);
        $customer->documents->transform(function ($document) {
            $document->path = asset('storage/app/public/customer_docs/' . $document->file);
            return $document;
        });
        $translations = __('Staffing_Company/Customer/crud');
        return view('StaffingCompany.Customer.update', compact('translations', 'customer'));
    }

    public function view($id)
    {
        $customer = Customer::with('documents', 'user')->find($id);
        $translations = __('Staffing_Company/Customer/crud');
        //dd($customer);
        return view('StaffingCompany.Customer.show', compact('customer', 'translations'));
    }

    // public function update(Request $request, $id)
    // {
    //     $customer = Customer::with('user')->find($id);

    //     if ($customer->user) {
    //         $updateData = [
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'contact_person1' => $request->contact,
    //             'phone' => _formatPhoneNumber($request->phone),
    //             'address' => $request->address,
    //             'city' => $request->city,
    //             'country' => $request->country,
    //         ];

    //         if ($request->filled('password')) {
    //             $updateData['password'] = Hash::make($request->password);
    //         }

    //         $customer->user->update($updateData);
    //     }

    //     // if ($customer->user) {
    //     //     $customer->user->update([
    //     //         'name' => $request->name,
    //     //         'email' => $request->email,
    //     //         'contact_person1' => $request->contact,
    //     //         'phone' => _formatPhoneNumber($request->phone),
    //     //         'address' => $request->address,
    //     //         'city' => $request->city,
    //     //         'country' => $request->country,
    //     //         'password' => Hash::make($request->password),
    //     //     ]);
    //     // }

    //     $customer->update([
    //         'name' => $request->name,
    //         'notes' => $request->notes,
    //     ]);

    //     if ($request->document_logs) {
    //         foreach ($request->document_logs as $document) {

    //             if ($document['file']) {

    //                 $file = $document['file'];
    //                 $fileName = time() . '_' . $file->getClientOriginalName();

    //                 $file->storeAs('customer_docs', $fileName, 'public');

    //                 $customer->documents()->create([
    //                     'type' => $document['doc_type'],
    //                     'expiry_date' => $document['expiry'],
    //                     'file' => $fileName,
    //                 ]);
    //             }
    //         }
    //     }

    //     if ($request->removedDocumentIds) {
    //         CustomerDocument::whereIn('id', $request->removedDocumentIds)->delete();
    //     }
    //     return response()->json([
    //         'message' => __('Staffing_Company/Customer/crud.customer_update')
    //     ]);
    // }
    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . ($customer->user->id ?? 'null'),
            'password' => 'nullable|max:255',
            'address' => 'required|max:255',
            'city' => 'nullable|max:255',
            'country' => 'nullable|max:255',
            'phone' => 'nullable|max:255',
        ]);
        if ($customer->user) {

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'contact_person1' => $request->contact,
                'phone' => _formatPhoneNumber($request->phone),
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $customer->user->update($updateData);
        }
        $customer->update([
            'name' => $request->name,
            'notes' => $request->notes,
        ]);


        if ($request->document_logs) {
            foreach ($request->document_logs as $document) {
                if (!empty($document['file'])) {

                    $file = $document['file'];
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('customer_docs', $fileName, 'public');

                    $customer->documents()->create([
                        'type' => $document['doc_type'],
                        'expiry_date' => $document['expiry'],
                        'file' => $fileName,
                    ]);
                }
            }
        }
        if ($request->removedDocumentIds) {
            CustomerDocument::whereIn('id', $request->removedDocumentIds)->delete();
        }

        return response()->json([
            'message' => __('Staffing_Company/Customer/crud.customer_update')
        ]);
    }



    public function destroy(Customer $customer)
    {

        $customer->user->delete();
        $customer->delete();

        return redirect()->back()->with('message', 'Customer Deleted Successfully!');
    }
}
