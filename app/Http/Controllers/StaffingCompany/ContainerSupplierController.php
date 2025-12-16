<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContainerSupplierRequest;
use App\Models\Role;
use App\Models\StaffingCompany\ContainerSupplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ContainerSupplierController extends Controller
{
    public function index()
    {
        $suppliers = ContainerSupplier::with('containerSupplierUserInfo')->get()->sortByDesc('created_at');

        return view('StaffingCompany.ContainerSupplier.index')->with('suppliers', $suppliers);
    }



    public function create()
    {
        $translations = __('Staffing_Company/Container_Supplier/crud');
        $common = __('Staffing_Company/common');
        return view('StaffingCompany.ContainerSupplier.create', compact('translations', 'common'));
    }

    public function store(ContainerSupplierRequest $request)
    {
        // Check if email already exists
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('Staffing_Company/Container_Supplier/crud.email_alert')
            ], 422);
        }
        DB::beginTransaction();

        $role = Role::where('name', 'supplier')->pluck('id')->first();
        try {
            // Create the user
            $user = new User();
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->role_id = $role;
            $user->name = $request->company_name;
            $user->slug = $request->company_name . "slug";
            $user->save();

            // Create the container supplier and link user_id
            $container_supplier = new ContainerSupplier();
            $container_supplier->company_name = $request->company_name;
            $container_supplier->user_id = $user->id;
            $container_supplier->code = $request->code;
            $container_supplier->telephone = _formatPhoneNumber($request->telephone);
            $container_supplier->mobile = _formatPhoneNumber($request->mobile);
            $container_supplier->email = $request->email;
            $container_supplier->address = $request->address;
            $container_supplier->city = $request->city;
            $container_supplier->post_code = $request->post_code;
            $container_supplier->notes = $request->notes;

            $container_supplier->save();
            DB::commit();
            return response()->json(['message' => __('Staffing_Company/Container_Supplier/crud.create_supplier_message')]);
        } catch (\Throwable $throwable) {
            DB::rollBack();

            // Log error for debugging
            \Log::error('Failed to create Container Supplier: ' . $throwable->getMessage());

            return $this->sendError('Error! ' . $throwable->getMessage(), [], 500);
        }
    }
    public function show(ContainerSupplier $container_supplier)
    {
        return response()->json($container_supplier);
    }

    public function view(ContainerSupplier $container_supplier)
    {
        $translations = __('Staffing_Company/Container_Supplier/crud');
        $common = __('Staffing_Company/common');
        return view('StaffingCompany.ContainerSupplier.show', compact('container_supplier', 'translations', 'common'));
    }

    public function edit(ContainerSupplier $container_supplier)
    {
        $translations = __('Staffing_Company/Container_Supplier/crud');
        $common = __('Staffing_Company/common');
        return view('StaffingCompany.ContainerSupplier.update', compact('container_supplier', 'translations', 'common'));
    }

    public function update(ContainerSupplierRequest $request, ContainerSupplier $container_supplier)
    {
        DB::beginTransaction();

        try {

            $user = $container_supplier->user;
            $user->email = $request->email;
            $user->name = $request->company_name;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $container_supplier->update([
                'company_name' => $request->company_name,
                'code'         => $request->code,
                'telephone'    => _formatPhoneNumber($request->telephone),
                'mobile'       => _formatPhoneNumber($request->mobile),
                'email'        => $request->email,
                'address'      => $request->address,
                'city'         => $request->city,
                'post_code'    => $request->post_code,
                'notes'    => $request->notes,
            ]);

            DB::commit();
            // return $this->sendResponse('Container Supplier Successfully updated');
            return response()->json(['message' => __('Staffing_Company/Container_Supplier/crud.update_supplier_message')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->sendError('Error! ' . $e->getMessage());
        }
    }


    public function destroy(ContainerSupplier $container_supplier)
    {
        $container_supplier->priceLists()->delete();
        $container_supplier->delete();

        return redirect()->back()->with('message', 'Supplier Deleted Successfully!');
    }

    public function createContainerSupplierPriceList(ContainerSupplier $container_supplier, Request $request)
    {
        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_10m3,
            'description' => $request->article_no_10m3_description,
            'price' => $request->price_10m3,
            'unit' => $request->unit_10m3,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_40m3,
            'description' => $request->article_no_40m3_description,
            'price' => $request->price_40m3,
            'unit' => $request->unit_40m3,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_sortable,
            'description' => $request->article_no_sortable_description,
            'price' => $request->price_sortable,
            'unit' => $request->unit_sortable,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_not_sortable,
            'description' => $request->article_no_not_sortable_description,
            'price' => $request->price_not_sortable,
            'unit' => $request->unit_not_sortable,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_industrial_waste,
            'description' => $request->article_no_industrial_waste_description,
            'price' => $request->price_industrial_waste,
            'unit' => $request->unit_industrial_waste,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_A_B_hout,
            'description' => $request->article_no_A_B_hout_description,
            'price' => $request->price_A_B_hout,
            'unit' => $request->unit_A_B_hout,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_C_hout,
            'description' => $request->article_no_C_hout_description,
            'price' => $request->price_C_hout,
            'unit' => $request->unit_C_hout,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Schoon_puin,
            'description' => $request->article_no_Schoon_puin_description,
            'price' => $request->price_Schoon_puin,
            'unit' => $request->unit_Schoon_puin,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Puin_Grof,
            'description' => $request->article_no_Puin_Grof_description,
            'price' => $request->price_Puin_Grof,
            'unit' => $request->unit_Puin_Grof,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Puin_met_10,
            'description' => $request->article_no_Puin_met_10_description,
            'price' => $request->price_Puin_met_10,
            'unit' => $request->unit_Puin_met_10,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Puin_met_25,
            'description' => $request->article_no_Puin_met_25_description,
            'price' => $request->price_Puin_met_25,
            'unit' => $request->unit_Puin_met_25,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Asfaltpuin,
            'description' => $request->article_no_Asfaltpuin_description,
            'price' => $request->price_Asfaltpuin,
            'unit' => $request->unit_Asfaltpuin,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Schoon_Gips,
            'description' => $request->article_no_Schoon_Gips_description,
            'price' => $request->price_Schoon_Gips,
            'unit' => $request->unit_Schoon_Gips,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Groenafval,
            'description' => $request->article_no_Groenafval_description,
            'price' => $request->price_Groenafval,
            'unit' => $request->unit_Groenafval,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Dakafval,
            'description' => $request->article_no_Dakafval_description,
            'price' => $request->price_Dakafval,
            'unit' => $request->unit_Dakafval,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Dakgrind,
            'description' => $request->article_no_Dakgrind_description,
            'price' => $request->price_Dakgrind,
            'unit' => $request->unit_Dakgrind,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Schoon_vlakglas,
            'description' => $request->article_no_Schoon_vlakglas_description,
            'price' => $request->price_Schoon_vlakglas,
            'unit' => $request->unit_Schoon_vlakglas,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Opbrengsten_metalen,
            'description' => $request->article_no_Opbrengsten_metalen_description,
            'price' => $request->price_Opbrengsten_metalen,
            'unit' => $request->unit_Opbrengsten_metalen,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_Opbrengsten_Papier,
            'description' => $request->article_no_Opbrengsten_Papier_description,
            'price' => $request->price_Opbrengsten_Papier,
            'unit' => $request->unit_Opbrengsten_Papier,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_field1,
            'description' => $request->article_no_field1_description,
            'price' => $request->price_field1,
            'unit' => $request->unit_field1,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_field2,
            'description' => $request->article_no_field2_description,
            'price' => $request->price_field2,
            'unit' => $request->unit_field2,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_field3,
            'description' => $request->article_no_field3_description,
            'price' => $request->price_field3,
            'unit' => $request->unit_field3,
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => $request->article_no_field4,
            'description' => $request->article_no_field4_description,
            'price' => $request->price_field4,
            'unit' => $request->unit_field4,
        ]);
    }
}
