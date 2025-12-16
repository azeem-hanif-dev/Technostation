<?php

namespace App\Http\Controllers\APIs\StaffingCompany\Auth;

use App\Models\StaffingCompany\EmployeeProjectPlanning;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\StaffingProject;
use App\Models\User;
use App\Models\Role;
use App\Models\StaffingCompany\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use JWTAuth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['signin', 'signinBySupervisor', 'signinBySupplier', 'signup']]);
    }

    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'device_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()
            ], 422);
        }

        $roleId = Role::where('name', 'user')->value('id');
        if (!$roleId) {
            return response()->json([
                'status' => 0,
                'message' => 'User role not found.'
            ], 500);
        }

        $user = new User();
        $user->role_id = $roleId;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->device_type = $request->device_type;
        $user->slug = Str::slug($request->name) . '-' . time();
        $user->password = Hash::make($request->password);
        $user->save();

        $token = JWTAuth::fromUser($user);

        $user = User::find($user->id);

        return response()->json([
            'status' => 1,
            'message' => 'Signup successful',
            'user' => $user,
            'token' => $token,

        ]);
    }

    public function signin(Request $request)
    {
        //supplier logic
        $user = User::where('email', $request->email)->first();
        if (!isset($user->id)) {
            return response()->json([
                'status' => false,
                'message' => "Not registered user or check email.",
            ]);
        }

        if ($user->role_id === 21) {
            try {
                $token = JWTAuth::attempt($request->only('email', 'password'), [
                    'exp' => Carbon::now()->addWeek()->timestamp, // (optional), added token expiry for one week
                ]);
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 500); // 500 => 'Internal Server Error',
            }

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 401); // 401 is unauthorized status code
            }
            return response()->json([$user,  $token]);
        }

        //user login
        $supervisor = Contact::where('email', $request->input('email'))->first();

        if (!$supervisor) {
            $user = User::where('email', '=', $request->email)->first();
            if (!isset($user->id)) {
                return response()->json([
                    'status' => false,
                    'message' => "Not registered user or check email.",
                ]);
            }

            try {
                $token = JWTAuth::attempt($request->only('email', 'password'), [
                    'exp' => Carbon::now()->addWeek()->timestamp, // (optional), added token expiry for one week
                ]);
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 500); // 500 => 'Internal Server Error',
            }

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 401); // 401 is unauthorized status code
            }

            // grab the user
            $user = User::find(Auth::id());
            $personnel = $user->personnel;

            try {
                $user->device_unique_id = $request->device_unique_id;
                $user->device_type = $user->device_type;
                $user->loggedIn = true;
                $user->lastLoggedIn = carbon::now()->setTimezone('Europe/Amsterdam');
                $user->save();
            } catch (Exception $e) {
                // do nothing
            }

            if ($personnel) {
                $project_planning = $personnel->planning;
            } else {
                $project_planning = null;
            }

            $customer_id = null;
            if ($project_planning) {
                $project = StaffingProject::find($project_planning->project_id);
                $customer_id = $project->customer_id;
            }

            $user->FCM_Token = $token;


            // if authenticated
            return response()->json([
                'status' => 1,
                'message' => 'Authenticated',
                'response' => [
                    'supervisor' => 'no',
                    'contact_id' => $customer_id,
                    'user' => $user->load('personnel'),
                    'token' => $token,
                ]
            ]);
        } else {

            $contact = Contact::where('email', $request->input('email'))->with('department.customer:id,name')
                ->first();
            $contact_data = $contact;
            if (!$contact) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Not Authenticated, Contact not exist.'
                ], 401); // 401 is unauthorized status code
            }

            if (!$contact->user_id) {
                $user = User::where('email', $request->input('email'))->first();
                if (!$user) {
                    $role_id = Role::where('name', '=', 'supervisor')->pluck('id')->first();
                    $user = new User;
                    $user->role_id = $role_id;
                    $user->email = $contact->email;
                    $user->name = $contact->first_name;
                    $user->password = $contact->password ? $contact->password : Hash::make('123456');
                    $user->slug = Str::slug($contact->first_name) . '-' . time();
                    $user->created_at = carbon::now();
                    $user->updated_at = carbon::now();
                    $user->save();
                }
                $user->password = $user->password ? $user->password : Hash::make('123456');
                $user->save();

                $contact->user_id = $user->id;
                $contact->password = $user->password;
                $contact->save();
            }

            try {
                $token = JWTAuth::attempt($request->only('email', 'password'), [
                    'exp' => Carbon::now()->addWeek()->timestamp, // (optional), added token expiry for one week
                ]);
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate' . $e->message()
                ], 500); // 500 => 'Internal Server Error',
            }

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate, Token'
                ], 401); // 401 is unauthorized status code
            }

            // grab the user
            //             $user = User::find(Auth::id());  hid this line on demand because the mobile app dev need the data from contact table

            $contactID = $contact->id;

            // if authenticated
            return response()->json([
                'status' => 1,
                'supervisor' => 'yes',
                //                 'user_info' => $user,
                'user_info' => $contact_data,

                'Projects' => $this->getProjectsList($contactID),
                'token' => $token
            ], 200);
        }
    }

    public function signinBySupplier(Request $request)
    {
        //supplier logic
        $user = User::where('email', $request->email)->first();
        if (!isset($user->id)) {
            return response()->json([
                'status' => false,
                'message' => "Not registered user or check email.",
            ]);
        }

        if ($user->role_id === 21) {
            $token = "";
            try {
                $token = JWTAuth::attempt($request->only('email', 'password'), [
                    'exp' => Carbon::now()->addWeek()->timestamp, // (optional), added token expiry for one week
                ]);
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 500); // 500 => 'Internal Server Error',
            }

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate'
                ], 401); // 401 is unauthorized status code
            }

            return response()->json(['user' => $user,  'token' => $token]);
        }
    }


    public function signinBySupervisor(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
            "password" => ['required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json([
                'status' => 0,
                'Error' => $er
            ], 201); // 500 => 'Internal Server Error',
        } else {

            $contact = Contact::where('email', $request->input('email'))->first();
            if (!$contact) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Not Authenticated, Contact not exist.'
                ], 401); // 401 is unauthorized status code
            }

            if (!$contact->user_id) {
                $user = User::where('email', $request->input('email'))->first();
                if (!$user) {
                    $role_id = Role::where('name', '=', 'supervisor')->pluck('id')->first();
                    $user = new User;
                    $user->role_id = $role_id;
                    $user->email = $contact->email;
                    $user->name = $contact->first_name;
                    $user->password = $contact->password ? $contact->password : Hash::make('123456');
                    $user->slug = Str::slug($contact->first_name) . '-' . time();
                    $user->created_at = carbon::now();
                    $user->updated_at = carbon::now();
                    $user->save();
                }
                $user->password = $user->password ? $user->password : Hash::make('123456');
                $user->save();

                $contact->user_id = $user->id;
                $contact->password = $user->password;
                $contact->save();
            }

            try {
                $token = JWTAuth::attempt($request->only('email', 'password'), [
                    'exp' => Carbon::now()->addWeek()->timestamp, // (optional), added token expiry for one week
                ]);
            } catch (JWTException $e) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate' . $e->message()
                ], 500); // 500 => 'Internal Server Error',
            }

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Could Not Authenticate, Token'
                ], 401); // 401 is unauthorized status code
            }

            // grab the user
            $user = User::find(Auth::id());
            $contactID = $contact->id;

            // if authenticated
            return response()->json([
                'status' => 1,
                'user_info' => $user,
                'Projects' => $this->getProjectsList($contactID),
                'token' => $token
            ], 200);
        }
    }


    public function me()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    public function signout()
    {
        try {
            $id = Auth::user()->id;
            $user = User::find($id);

            JWTAuth::invalidate(JWTAuth::getToken());

            $user->fcm_token = '';
            $user->FCM_TOKEN = '';
            $user->loggedIn = false;
            $user->save();

            return response()->json([
                'status' => 1,
                'message' => "Sign out successfully"
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to logout, please try again.'
            ], 500);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard()
    {
        return Auth::guard();
    }


    ///////////////////// Private functions below /////////////////////

    private function getProjectsList($id)
    {
        return StaffingProject::where('performer', '=', $id)->where('active', '=', '1')->orderBy('id', 'desc')->get();
    }

    public function setFCM(Request $request)
    {
        $rules = [

            'FCM_TOKEN' => ['required'],
            "UserID" => ['required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $er = $validator->errors();
            return response()->json($er, 201);
        } else {
            $ValidData = $request->input();
        }
        $User = User::find($ValidData['UserID']);
        // return $User;
        if ($User) {
            // return $User;

            $User->FCM_Token = $ValidData['FCM_TOKEN'];
            if ($User->save()) {
                return response()->json([
                    'Message' => "FCM Token Updated Successfully",
                    'Status' => "Success"
                ], 200);
            } else {
                return response()->json([
                    'Message' => "Can not update FCM Token",
                    'Status' => "Success"
                ], 200);
            }
        } else {
            return response()->json([
                'Message' => "User no exist",
                'Status' => "Fail"
            ], 201);
        }
    }


    public function password(Request $request)
    {
        $rules = [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => 'Current password is incorrect.'
            ], 400);
        }


        $user->password = Hash::make($request->new_password);
        $user->update();

        return response()->json([
            'message' => 'Password updated successfully.'
        ], 200);
    }
}
