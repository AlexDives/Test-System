<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class persController extends Controller
{
    public function persList(Request $request)
    {
        $personList = DB::table('persons')->where('is_block', 'F')->paginate(10);
        return view('testing.pers.list', ['persList' => $personList, 'role_id' => session('role_id')]);
    }

    public function persCheck(Request $request)
    {
        if (DB::table('persons')->where('id', $request->pid)->where('pin', $request->pin)->exists()) {
            session(['pers_id' => $request->pid]);
            return 'true';
        } else return 'false';
    }

    public function persCabinet(Request $request)
    {

        $persTests = DB::table('pers_tests')
            ->where('pers_tests.pers_id', session('pers_id'))
            ->leftjoin('tests', 'tests.id', 'pers_tests.test_id')
            ->select(
                'pers_tests.id',
                'tests.discipline',
                'pers_tests.status',
                'pers_tests.start_time',
                'pers_tests.end_time',
                'pers_tests.test_ball_correct',
                'pers_tests.last_active',
                'tests.min_ball'
            )
            ->get();
        foreach ($persTests as $pt) {
            if ($pt->last_active != null) {
                $timestampStart = strtotime($pt->last_active);
                $timestampEnd = time();
                $seconds = ($timestampEnd - $timestampStart);
                if ($seconds >= 61) {
                    DB::table('pers_tests')
                        ->where('id', $pt->id)
                        ->update(['status' => 3]);
                }
            }
        }
        $persTests = DB::table('pers_tests')
            ->where('pers_tests.pers_id', session('pers_id'))
            ->leftjoin('tests', 'tests.id', 'pers_tests.test_id')
            ->select(
                'pers_tests.id',
                'tests.discipline',
                'pers_tests.status',
                'pers_tests.start_time',
                'pers_tests.end_time',
                'pers_tests.test_ball_correct',
                'pers_tests.last_active',
                'tests.min_ball',
                'pers_tests.minuts_spent'
            )
            ->orderby('pers_tests.id', 'desc')
            ->get();
        $pers = DB::table('persons')->where('id', session('pers_id'))->first();
        return view('testing.pers.cabinet', [
            'persTests' => $persTests,
            'person'    => $pers,
            'role'      => session('role_id')
        ]);
    }
}
