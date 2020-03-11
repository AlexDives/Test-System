<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class testResultController extends Controller
{
    public function short(Request $request)
    {
        return view('testing.testResults.short');
    }

    public function full(Request $request)
    {
        return view('testing.testResults.full');
    }
}
