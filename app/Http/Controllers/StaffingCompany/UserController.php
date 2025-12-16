<?php

namespace App\Http\Controllers\StaffingCompany;

use App\Helpers\Helper; 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit()
    {
        $user = \Auth::user();
        $translations = __('Staffing_Company/common');
        return view('StaffingCompany.UserProfile.update',compact('user', 'translations'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = User::find($request->user_id);

        $user->update([
           'name' => $request->name,
           'notes' => $request->notes,
           'phone' => _formatPhoneNumber($request->phone),
           'postcode' => $request->postcode,
           'language' => $request->language,
           'houseNumber' => $request->houseNumber,
           'city' => $request->city,
        ]);
            return response()->json([
            'status' => true,
            'message' => __('Staffing_Company/User/crud.user_update')
        ]);
    }
}
