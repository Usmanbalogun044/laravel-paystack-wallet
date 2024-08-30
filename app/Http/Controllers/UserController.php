<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function landingPage(){
        $wallet=Auth::user()->wallet;
        return view('dashboard', compact('wallet'));
    }
}
