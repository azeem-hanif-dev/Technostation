<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\EmployeeFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeFunctionsController extends Controller
{
    public function index()
    {
        $employee_functions = EmployeeFunction::orderByDesc('created_at')->get();
        return view('StaffingCompany.EmployeeFunction.index', compact('employee_functions'));
    }

    public function create()
    {
        $translations = __('Staffing_Company/Staff_Function/crud');
        return view('StaffingCompany.EmployeeFunction.create', compact('translations'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $function = new EmployeeFunction();
            $function->name = $request->name;
            $function->code = $request->code;
            $function->save();

            DB::commit();

            return response()->json([
                'message' => __('Staffing_Company/Staff_Function/crud.function_create')
            ]);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Failed to create Employee Function: ' . $throwable->getMessage());

            return response()->json([
                'error' => 'Error! ' . $throwable->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json(EmployeeFunction::find($id));
    }

    public function view($id)
    {
        $translations = __('Staffing_Company/Staff_Function/crud');
        return view('StaffingCompany.EmployeeFunction.show', compact('id', 'translations'));
    }

    public function edit($id)
    {
        $translations = __('Staffing_Company/Staff_Function/crud');
        return view('StaffingCompany.EmployeeFunction.update', compact('id', 'translations'));
    }

    public function update(Request $request, $id)
    {
        EmployeeFunction::where('id', $id)->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return response()->json([
            'message' => __('Staffing_Company/Staff_Function/crud.function_update')
        ]);
        // return response()->json(['message' => 'Function updated successfully.']);
    }

    public function destroy(EmployeeFunction $employeeFunction)
    {
        $employeeFunction->delete();
        // return redirect()->back()->with('message', 'Staffing_Company/Staff_Function/crud.function_update');
        return redirect()->back()->with('message', __('Staffing_Company/Staff_Function/crud.function_delete'));

    }
}
