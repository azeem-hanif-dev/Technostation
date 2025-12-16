<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if($request->user()->hasRole('superadmin')){
        return view('Super_Admin.dashboard');
      }

      else if($request->user()->hasRole('admin'))
      {//total projects, total workers,
        //App::setLocale($request->user()->language);
        //$request->session()->put('locale', $request->user()->language);
        //App::setLocale($request->user()->language);
        //Session::get('locale');

        //dd($request->user()->language);

        $request->session()->put('locale', $request->user()->language);
        App::setLocale($request->user()->language);
        Session::get('locale');

        $id = $request->user()->id;
        $totalProjects = DB::table('projects')
                           ->where('company_id','=',$id)
                           ->count();

        $totalCustomers = DB::table('customers')
                            ->where('company_id','=',$id)
                            ->count();

        $totalWorkers = DB::table('users')
                           //->where('worker_type_id','!=','')
                           ->where('company_id','=',$id)
                           ->where('status','=',1)
                           ->count();

        $totalReports = DB::table('inspections_review')
                           ->where('company_id','=',$id)
                           ->count();

        $totalExternalReports = DB::table('external_reports')
                           ->where('company_id','=',$id)
                           ->count();

        return view('Company_Admin.dashboard')
            ->withTotalProjects($totalProjects)
            ->withTotalCustomers($totalCustomers)
            ->withTotalReports($totalReports)
            ->withTotalExternalReports($totalExternalReports)
            ->withTotalWorkers($totalWorkers);
      }

      else if($request->user()->hasRole('user'))
      {
        return view('User.dashboard');
      }

      else if($request->user()->hasRole('customer'))
      {
        return view('Customer.dashboard');
      }

        // return view('home');
    }
}
