<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class testResultController extends Controller
{
    public function short(Request $request)
    {
        $pdf = PDF::loadView('testing.testResults.short');
        if ($request->t == 'pdf')
            return $pdf->stream();
        else
            return view('testing.testResults.short');
    }

    public function full(Request $request)
    {
        return view('testing.testResults.full');
    }
}
