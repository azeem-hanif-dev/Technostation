<?php

namespace App\Http\Controllers\APIs\StaffingCompany;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StaffingCompany\Contact;
use Illuminate\Support\Facades\Auth;

class Profile extends Controller
{

    public function updateBasicInfo(Request $request, $id = null)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'address'    => 'nullable|string|max:255',
        ]);

        $authUser = Auth::user();

        if ($id) {
            $contact = Contact::findOrFail($id);
            if ($contact->user_id !== $authUser->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can only update your own contact details.',
                ], 403);
            }
            $contact->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Supervisor contact name updated successfully.',
                'contact' => $contact,
            ]);
        }
        if (!$authUser->personnel) {
            return response()->json([
                'status' => false,
                'message' => 'User personnel not found.',
            ], 404);
        }

        $personnel = $authUser->personnel;
        if ($personnel->user_id !== $authUser->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this personnel.',
            ], 403);
        }
        $personnel->first_name = $request->first_name;
        $personnel->last_name = $request->last_name;
        $personnel->address = $request->address;
        $personnel->save();

        return response()->json([
            'status' => true,
            'message' => 'Personnel details updated successfully.',
            'personnel' => $personnel,
        ]);
    }
    public function picture_update(Request $request)
    {
        
        $request->validate([
            'picture' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $contact = _supervisor();
        if($contact){

            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('public/contact_pictures');
                $contact->picture = basename($picturePath);
            }
            $contact->save();

            return response()->json([
                'status' => true,
                'message' => 'Supervisor picture updated successfully.',
                'personnel' => $contact,
            ]);
        }
        $user = _user();

        if (!$user || !$user->personnel) {
            return response()->json([
                'status' => false,
                'message' => 'User or personnel not found.',
            ], 404);
        }
        $personnel = $user->personnel;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('public/personnel_pictures');
            $personnel->picture = basename($picturePath);
        }
        $personnel->save();

        return response()->json([
            'status' => true,
            'message' => 'Worker picture updated successfully.',
            'personnel' => $personnel,
        ]);
    }
}
