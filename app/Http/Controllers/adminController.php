<?php

namespace App\Http\Controllers;

use PDF;
use Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function main()
    {

        $persons = DB::table('persons')->orderby('id', 'desc')->get();
        DB::table('persons')->where('email', 'anokhin-toni@mail.ru')->update(['password' => Hash::make('12345')]);

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
    
    function sendAllMailWithAttach(Request $request)
    {
        //persons = DB::table('persons')->where('id', 15)->get(); 
        //hometesting : uvGJTrRNU475

        $persons = DB::table('persons')->where('send_mail', 0)->whereNotNull('email')->whereNull('secret_string')->get();
        foreach ($persons as $pers) {
            Mail::send('admin.ajax.templateEmailWithAttach', [], function ($message) use ($pers) {
                $message->from('asu@ltsu.org', 'Тех. поддержка ЛНУ имени Тараса Шевченко');
                $message->to($pers->email, $pers->email)->subject('Инструкция для прохождения тестирования в режиме On-line');
                $message->attach('https://test.ltsu.org/files/helper.docx');
            });
            DB::table('persons')->where('id', $pers->id)->update(['send_mail' => 1]);
        }
        return 0;
    }

    function statistic(Request $request)
    {
        $events = DB::table('events')->get();
        
        return view('admin.statistic', [
            'role'      => session('role_id'),
            'events'    => $events
        ]);
    }

    function load_statisctic(Request $request)
    {
        $mer_id = $request->mid;
        $event_tests = DB::table('event_tests as et')
                    ->leftjoin('tests as t', 't.id', 'et.test_id')
                    ->leftjoin('type_test as tt', 'tt.id', 't.type_id')
                    ->where('et.event_id', $mer_id)
                    ->select('et.*', 't.discipline', 'tt.name as type')
                    ->get();
        $persCount  = DB::table('pers_events')->where('event_id', $mer_id)->get();

        $count_pers_in_test = DB::table('pers_events as pe')
                    ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                    ->where('pt.status', '1')
                    ->where('pe.event_id', $mer_id)
                    ->count();

        $count_pers_end_test = DB::table('pers_events as pe')
                    ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                    ->where('pt.status', '2')
                    ->where('pe.event_id', $mer_id)
                    ->count();

        $count_pers_pause_test = DB::table('pers_events as pe')
                    ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                    ->where('pt.status', '3')
                    ->where('pe.event_id', $mer_id)
                    ->count();

        $persCount_bad = 0;
        $count_pers_in_test = 0;
        $count_pers_end_test = 0;
        $count_pers_pause_test = 0;
        foreach($persCount as $pc) 
        {
            if (DB::table('pers_tests')->where('pers_id', $pc->pers_id)->where('pers_event_id', $pc->id)->count() == 0) $persCount_bad++;
            if (DB::table('pers_tests')->where('pers_id', $pc->pers_id)->where('pers_event_id', $pc->id)->where('status', '1')->count() > 0) $count_pers_in_test++;
            if (DB::table('pers_tests')->where('pers_id', $pc->pers_id)->where('pers_event_id', $pc->id)->where('status', '2')->count() > 0) $count_pers_end_test++;
            if (DB::table('pers_tests')->where('pers_id', $pc->pers_id)->where('pers_event_id', $pc->id)->where('status', '3')->count() > 0) $count_pers_pause_test++;
        }
        
        $persCount  = $persCount->count();
        
        $tests = [];
        foreach ($event_tests as $et) {
            $count_pers = DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->count();

            $max_ball = DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->max('pt.test_ball_correct');

            $fio_max_ball = DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->leftjoin('persons as p', 'p.id', 'pt.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->where('pt.test_ball_correct', $max_ball)
                            ->first();

            $min_ball = DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->min('pt.test_ball_correct');

            $fio_min_ball = DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->leftjoin('persons as p', 'p.id', 'pt.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->where('pt.test_ball_correct', $min_ball)
                            ->first();

            $sred_ball = round(DB::table('pers_events as pe')
                            ->leftjoin('pers_tests as pt', 'pt.pers_id', 'pe.pers_id')
                            ->where('pe.event_id', $mer_id)
                            ->where('pt.test_id', $et->test_id)
                            ->avg('pt.test_ball_correct'),2 );

            $tests += [
                $et->test_id => (object)[
                    'id'            => $et->test_id,
                    'discipline'    => $et->discipline,
                    'type'          => $et->type,
                    'countPers'     => $count_pers != null ? $count_pers : 0,
                    'max_ball'      => $max_ball != null ? $max_ball : 0,
                    'fio_max_ball'  => $fio_max_ball != null ? $fio_max_ball->famil.' '.$fio_max_ball->name.' '.$fio_max_ball->otch : '',
                    'min_ball'      => $min_ball != null ? $min_ball : 0,
                    'fio_min_ball'  => $fio_min_ball != null ? $fio_min_ball->famil.' '.$fio_min_ball->name.' '.$fio_min_ball->otch : '',
                    'sred_ball'     => $sred_ball != null ? $sred_ball : 0
                ]
            ];
        }

        //dd((object)$tests);
        return view('admin.ajax.listTest', [
            'tests'                     => $tests,
            'persCount'                 => $persCount,
            'persCount_bad'             => $persCount_bad,
            'count_pers_in_test'        => $count_pers_in_test,
            'count_pers_end_test'       => $count_pers_end_test,
            'count_pers_pause_test'     => $count_pers_pause_test
        ]);
    }
}
