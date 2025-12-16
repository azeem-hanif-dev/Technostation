<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\StaffingCompany\OptionList;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\UserRight;
use App\Models\User;
use Illuminate\Http\Request;

class UserRightsController extends Controller
{
    public function index()
    {
        $data = $this->getIndexData();
        $options = OptionList::all()->sortByDesc('created_at');

        return view('StaffingCompany.UserOptions.index', compact('data','options'));
    }

    public function create()
    {
        $users = $this->getWebUsers();
        $roles = Role::all();
        $options = OptionList::all();

        return view('StaffingCompany.UserOptions.create', compact('users', 'roles', 'options'));
    }

    public function store(Request $request)
    {
        UserRight::updateOrCreate([
            'user_id' => $request->user_id,
        ],[
            'role_id' => $request->role_id,
            'active' => $request->status,
        ]);

        return response()->json([
            'status'  => true,
            'message' => __('Staffing_Company/User_Right/crud.create_right'),
        ], 200);
    }
    
           

    public function show($id)
    {
        $user_right = UserRight::find($id);
        return response()->json($user_right);
    }

    public function edit($id)
    {
        $user_right = UserRight::find($id);

        $users = $this->getWebUsers();
        $roles = Role::all();
        $options = OptionList::all();
        $user_profile = User::find($user_right->user_id);

        return view('StaffingCompany.UserOptions.update',compact('id','user_profile','users','roles','options'));
    }

    public function update(Request $request, $id)
    {
        UserRight::where('id',$id)->update([
            'role_id' => $request->role_id,
            'active' => $request->status,
        ]);

        return response()->json([
            'message' => __('Staffing_company/User_Right/crud.update_right'),
            'status'  => true
        ], 200);
    }

    public function destroy($id)
    {
    }

    public function saveUserRights(Request $request, $user_right_id)
    {
        $user_right = UserRight::find($user_right_id);

        $user_right->rightsList()->sync($request->module_ids);

        return redirect()->route('user-options.index');
    }

    private function getIndexData(){
        $data = [];
        $user_options = UserRight::all();

        foreach ($user_options as $user_option) {
            $user = User::find($user_option->user_id);
            $role = Role::find($user_option->role_id);
            $status = $user_option->active == 1 ? 'Active' : 'Inactive';

            $data[] = [
                'id' => $user_option->id,
                'user' => $user,
                'user_rights' => $user_option->rightsList,
                'role' => $role,
                'status' => $status,
            ];
        }

        return $data;
    }

    private function getWebUsers(){
        $current_user = _user();

        $user_ids = Personnel::where('staff_type_id',2)
            ->pluck('user_id')
            ->unique()
            ->toArray();

        return User::whereIn('id',$user_ids)->where('id','!=',$current_user->id)->get();
    }
}
