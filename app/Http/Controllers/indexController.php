<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index(Request $request){
        $idRole = -1;
        if ($request->session()->has('user_id') || $request->session()->has('person_id')) {
            $idRole = session('role_id');
        }
        return view('index', ['idRole' => $idRole]);
    }
}
