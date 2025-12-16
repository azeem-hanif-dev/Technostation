<?php

namespace App\Http\Controllers\StaffingCompany;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    //

    public function index()
    {
        //$customers = Customer::all();
        return view('StaffingCompany.Map.map');
    }
}
