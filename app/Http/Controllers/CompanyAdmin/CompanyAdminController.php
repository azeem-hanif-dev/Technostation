<?php

namespace App\Http\Controllers\CompanyAdmin;
use Auth;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyAdminController extends Controller
{
  public function index(){
    return redirect()->route('home');
  }

  public function companyProfile()
  {
    return view('Company_Admin.profile');
  }

  public function companyDetail()
  {
    $id = Auth::user()->id;
    $comapny_data = User::find($id);

    return response()->json([
      'company_data' => $comapny_data,
    ],200);
  }

  public function companyUpdateDetail(Request $request)
  {
    $id = Auth::user()->id;
    $comapany = User::find($id);
    $comapany->email = $request->email;
    $comapany->password = Hash::make($request->password);
    $comapany->language = $request->language;
    $comapany->save();

    return response()->json([
      'status' => true,
    ],200);
  }
}
