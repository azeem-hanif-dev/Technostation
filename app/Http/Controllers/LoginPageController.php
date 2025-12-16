<?php

namespace App\Http\Controllers;

class LoginPageController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
}
