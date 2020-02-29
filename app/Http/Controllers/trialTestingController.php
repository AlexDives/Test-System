<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class trialTestingController extends Controller
{
    public function index(Request $request){
        
        return view('persons.trialTesting');
    }
}
