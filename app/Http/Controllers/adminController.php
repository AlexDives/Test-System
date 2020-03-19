<?php

namespace App\Http\Controllers;

use PDF;
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function main()
    {

        $persons = DB::table('persons')->orderby('id', 'desc')->get();

        $persTests = [];
        foreach ($persons as $p)
        {
            $persTests += [$p->id => 
                    DB::table('pers_tests')
                    ->where('pers_tests.pers_id', $p->id)
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
                    ->get()
                
            ];
        }

        return view('admin.main', [
            'persTests' => $persTests,
            'pers'      => $persons,
            'role'      => session('role_id')
        ]);
    }

    
    function sendAllMail(Request $request)
    {
        //persons = DB::table('persons')->get();
        $persons = DB::table('persons')->get();
        foreach ($persons as $pers) {
            $fio = $pers->famil.' '.$pers->name.' '.$pers->otch;
            $text = $request->texta;
            $theme = $request->theme;
        
            Mail::send('admin.ajax.templateEmail', ['text' => $text, 'fio' => $fio], function ($message) use ($pers, $theme) {
                $message->from('asu@ltsu.org', 'Тех. поддержка ЛНУ имени Тараса Шевченко');
                $message->to($pers->email, $pers->email)->subject($theme);
            });
        }
        return 0;
    }
}
