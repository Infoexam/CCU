<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function student()
    {
        return view('welcome');
    }

    public function admin()
    {
        return view('admin.home');
    }

    public function exam()
    {
        //
    }
}
