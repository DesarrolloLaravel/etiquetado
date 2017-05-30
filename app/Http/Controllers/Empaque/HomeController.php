<?php

namespace App\Http\Controllers\Empaque;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function home()
    {
        //
        return view('empaque.dashboard');
    }
}
