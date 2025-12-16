<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use Illuminate\Support\Facades\Auth;
use Lang;
use Session;


class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $request->session()->put('locale', $request->language);
        App::setLocale($request->language);
        return Session::get('locale');
        Session::flash('success', 'Language changed');

    }
    public function changeLanguagelanding($lang)
    {

        App::setLocale($lang);
        $locale = App::getLocale();

        //dd($locale);
        session()->put("locale",$lang);
        return redirect()->back();

    }

    public function landingPage()
    {
        $module=App\Models\ModulePrice::all();
      return view('landing_page.index_dup',compact('module'));
    }



    public function monthlyFormData(Request $request){
        if (Auth::check()){

            $monthly=new App\Models\AdminModule();
            $monthly->module_names=$request->module_name;
            $monthly->module_type=$request->type_monthly;
            $monthly->count_module=$request->module_count;
            $monthly->module_actual_amount=$request->total_amount;
            $monthly->module_amount_save=$request->save;
            $monthly->module_subtotal_amount=$request->sub_total;
            $monthly->module_total_amount=$request->total;
            $monthly->user_id=Auth::id();
            $monthly->save();

            return \response()->json(['success'=>1]);


        }
        else{
            return \response()->json(['fail'=>$request->all()]);
        }


    }

    public function yearlyFormData(Request $request){
        if (Auth::check()){
            $monthly=new App\Models\AdminModule();
            $monthly->module_names=$request->module_name1;
            $monthly->module_type=$request->type_yearly1;
            $monthly->count_module=$request->module_count1;
            $monthly->module_actual_amount=$request->total_amount1;
            $monthly->module_amount_save=$request->save1;
            $monthly->module_subtotal_amount=$request->sub_total1;
            $monthly->module_total_amount=$request->total1;
            $monthly->user_id=Auth::id();
            $monthly->save();
            return \response()->json(['success'=>1]);


        }
        else{
            return \response()->json(['fail'=>$request->all()]);
        }


    }


}
