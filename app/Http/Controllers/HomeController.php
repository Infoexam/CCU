<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin', ['only' => 'admin']);

        //$this->middleware('auth', ['only' => 'exam']);
    }

    /**
     * 網站首頁
     *
     * @return \Illuminate\View\View
     */
    public function student()
    {
        return view('home');
    }

    /**
     * 管理頁面
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        return view('admin.home');
    }

    /**
     * 考試頁面
     *
     * @return \Illuminate\View\View
     */
    public function exam()
    {
        //
    }
}
