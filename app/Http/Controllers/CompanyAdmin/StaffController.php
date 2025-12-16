<?php

namespace App\Http\Controllers\CompanyAdmin;

use DB;
use PDF;
use App;
use Hash;
use Auth;
use Lang;
use Session;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\EmployAgency;
use App\Models\Permission;
use App\Models\WorkerType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $company_id = Auth::user()->id;
      $roles_array = ['user','supervisor','manager'];
      $role_ids = Role::whereIn('name',$roles_array)->pluck('id');
      $users = User::with('role', 'agency')
                    ->where('company_id','=',$company_id)
                    ->where('status','=',1)
                    ->whereIn('role_id',$role_ids)
                    ->get();

                      // ->where('status','=',1)
      // dd($users[2]->isInspector());
      return view('Company_Admin.staff.index')->withUsers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company_id = Auth::user()->id;
        $permissions = Permission::all();
        $agencies = EmployAgency::where('company_id', '=', $company_id)
                                ->pluck('name','id');
        $types = WorkerType::pluck('name','id');
        $manager_role_id = Role::where('name','=','manager')
                                  ->pluck('id')
                                  ->first();

        $supervisor_role_id = Role::where('name','=','supervisor')
                                  ->pluck('id')
                                  ->first();

       $supervisors = User::where('company_id','=',$company_id)
                            ->where('status','=',1)
                            ->where('role_id','=',$supervisor_role_id)
                            ->pluck('name','id');

       $managers = User::where('company_id','=',$company_id)
                            ->where('status','=',1)
                            ->where('role_id','=',$manager_role_id)
                            ->pluck('name','id');
// dd($types);
        return view('Company_Admin.staff.add')
                ->withAgencies($agencies)
                ->withSupervisors($supervisors)
                ->withManagers($managers)
                ->withPermissions($permissions)
                ->withTypes($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return $request->all();
      $request->validate([
        'name' => 'required | max:255',
        'email' => 'required | email | unique:users,email',
        'password' => 'required|min:6|max:255',
//        'phone' => 'required',
//        'address' => 'required|max:255',
//        'postcode' => 'required',
//        'houseNumber' => 'required',
//        'city' => 'required|max:255',
//        'country' => 'required|max:255',
    ]);

        if($request->role == 'worker'){
            $request->validate([
                'worker_type_id' => 'required|max:255',
                'supervisor_id' => 'required|max:255',
            ]);
            $role_id = Role::where('name','=','user')
                            ->pluck('id')
                            ->first();
        }else if ($request->role == 'supervisor') {
            $request->validate([
                'manager_id' => 'required|max:255',
            ]);

            $role_id = Role::where('name','=','supervisor')
                            ->pluck('id')
                            ->first();
        }else {
            $role_id = Role::where('name','=','manager')
                            ->pluck('id')
                            ->first();
        }
      $company_id = Auth::user()->id;
      $user = new User;
      $user->role_id = $role_id;
      $user->email = $request->email;
      $user->name = $request->name;
      $user->password = Hash::make($request->password);
      $user->slug = Str::slug($request->name) . '-' . time();
      $user->phone = $request->phone;
      $user->company_id = $company_id;
      $user->employment_agency_id = $request->agency_id;
      $user->worker_type_id = $request->worker_type_id;

      $user->address = $request->address;
      $user->postcode = $request->postcode;
      $user->houseNumber = $request->houseNumber;
      $user->city = $request->city;
      $user->country = $request->country;
      $user->email_verified_at = null;
      $user->created_at = carbon::now();
      $user->updated_at = carbon::now();

      if ($request->role == 'worker') {
         $user->reports_to_id = $request->supervisor_id;
      }else if ($request->role == 'supervisor') {
         $user->reports_to_id = $request->manager_id;
      }
      $user->save();
      foreach ($request->permissions as $key => $permission) {
        $user->permissions()->attach((int)$permission);
      }
      return response()->json([
              'message' => 'success',
              'status'  => 1
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        // dd($user->companyName);
        return view('Company_Admin.staff.view')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function edit_vue($id)
     {
         $user = User::findOrFail($id);
         return view('Company_Admin.staff.edit')
                     ->withId($id);
     }

    public function edit($id)
    {
      $user = User::findOrFail($id);
      $types = WorkerType::pluck('name','id');

      $assigned_perm = $user->permissions;
      $data =array();

      foreach ($assigned_perm as $key => $value)
      {
        $data[$key] = $value->id;
      }
      $status = array();
      $permissions = Permission::all();


      foreach ($permissions as $key => $permission)
      {
        if(in_array($permission->id, $data))
            {
              $status[$key]['name'] = $permission->name;
              $status[$key]['id'] = $permission->id;
              $status[$key]['before'] = true;
              $status[$key]['edit'] = false;
            }//already checked
        else
            { $status[$key]['name'] = $permission->name;
              $status[$key]['id'] = $permission->id;
              $status[$key]['before'] = false;
              $status[$key]['edit'] = false;
            }
      }
      $roles_array = ['user','supervisor','manager'];
      $roles = Role::whereIn('name',$roles_array)->pluck('name','id');
     // dd($roles);
      $company_id = Auth::user()->id;
      $agencies = EmployAgency::where('company_id','=',$company_id)
                              ->pluck('name','id');
      $manager_role_id = Role::where('name','=','manager')
                                ->pluck('id')
                                ->first();

      $supervisor_role_id = Role::where('name','=','supervisor')
                                ->pluck('id')
                                ->first();

     $supervisors = User::where('company_id','=',$company_id)
                          ->where('role_id','=',$supervisor_role_id)
                          ->where('status','=',1)
                          ->pluck('name','id');

     $managers = User::where('company_id','=',$company_id)
                          ->where('role_id','=',$manager_role_id)
                          ->where('status','=',1)
                          ->pluck('name','id');

// dd($supervisors);
      return view('Company_Admin.staff.edit')
                  ->withUser($user)
                  ->withTypes($types)
                  ->withAgencies($agencies)
                  ->withRoles($roles)
                  ->withSupervisors($supervisors)
                  ->withManagers($managers)
                  ->withStatus($status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
      $request->validate([
        'name' => 'required|max:255',
        'email' => "required|email|unique:users,email,$id",
//        'phone' => 'required',
        'password' => 'nullable|min:6|max:255',
//        'address' => 'required|max:255',
//        'postCode' => 'required',
//        'houseNumber' => 'required',
//        'city' => 'required|max:255',
//        'country' => 'required|max:255',
        ]);
        // |regex:/^(\+31)[0-9]{9}$/
        if($request->role == 'user'){
            $request->validate([
                'worker_type_id' => 'required|max:255',
                'supervisor_id' => 'required|max:255',
            ]);
            $role_id = Role::where('name','=','user')
                            ->pluck('id')
                            ->first();
        }else if ($request->role == 'supervisor') {
            $request->validate([
                'manager_id' => 'required|max:255',
            ]);

            $role_id = Role::where('name','=','supervisor')
                            ->pluck('id')
                            ->first();
        }else {
            $role_id = Role::where('name','=','manager')
                            ->pluck('id')
                            ->first();
        }

      $user = User::findOrFail($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->role_id = $role_id;
      $user->phone = $request->phone;
      $user->employment_agency_id = $request->agency;
      $user->worker_type_id = (int)$request->worker_type;
      $user->address = $request->address;
      $user->city = $request->city;
      $user->zipcode = $request->zipcode;
      $user->postCode = $request->postCode;
      $user->houseNumber = $request->houseNumber;
      $user->country = $request->country;
      $user->fax = $request->fax;
      $user->notes = null;
      if(isset($request->password)){
        $user->password = Hash::make($request->password);
      }
      $user->email_verified_at = null;
      $user->updated_at = carbon::now();
      if ($request->role == 'user') {
         $user->reports_to_id = $request->supervisor_id;
      }else if ($request->role == 'supervisor') {
         $user->reports_to_id = $request->manager_id;
      }
      $user->save();

      if ($request->role == 'supervisor') {
          DB::table('users_permissions')->where('user_id','=',$user->id)->delete();

          $permissions =Permission::all();
          foreach ($permissions as $key => $permission) {
            $val = "Permission".$key;
            if($request->$val){
              $user->permissions()->attach((int)$request->$val);
            }
          }
      }
      if (App::getLocale() == "en") {
        Session::flash('success','Staff updated successfully');
      }else {
        Session::flash('success','Personeelsinfo succesvol bijgewerkt');
      }
      return redirect()->route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd('destroy');
    }

    public function deleteRecord($id)
    {
      DB::table('users')
            ->where('id', $id)
            ->update([
              'status' => 0,
              'name' => "No Data",
              'phone' => "No Data",
              'address' => "No Data",
              'city' => "No Data",
              'country' => "No Data",
              'email' => $id."no_data@".$id.".null",
            ]);

      return redirect()->route('staff.index');
    }

    public function statusChange(User $user)
    {
      DB::table('users')
            ->where('id', $user->id)
            ->update([
              'status' => ! $user->status,
              'name' => "No Data",
              'phone' => "No Data",
              'address' => "No Data",
              'city' => "No Data",
              'country' => "No Data",
              'email' => "No Data",
            ]);

      return redirect()->route('staff.index');
    }
    public function staffPdf($user_id)
    {
        $user = User::find($user_id);
        // $project = Project::where('projects.id','=',$project_id)->first();
        // dd($project->jobs);
        //$user = $project->jobs;
        $pdf = PDF::loadView('Company_Admin.staff.staffPdf',compact('user'))->setPaper('a4', 'portrait');

        return $pdf->download('staffDetail.pdf');
    }

    public function getStaffEditDetails($id)
    {
      $user = new UserResource(User::findOrFail($id));
      $agencies = EmployAgency::pluck('name','id');
      $types = WorkerType::pluck('name','id');
      $company_id = Auth::user()->id;
      $manager_role_id = Role::where('name','=','manager')
                                ->pluck('id')
                                ->first();

      $supervisor_role_id = Role::where('name','=','supervisor')
                                ->pluck('id')
                                ->first();
     $supervisors = User::where('company_id','=',$company_id)
                          ->where('role_id','=',$supervisor_role_id)
                          ->get();
     $managers = User::where('company_id','=',$company_id)
                          ->where('role_id','=',$manager_role_id)
                          ->get();

       return response()->json([
           'agencies' => $agencies,
           'types' => $types,
           'user' => $user,
           'supervisors' => $supervisors,
           'managers' => $managers,
       ]);
    }
}
