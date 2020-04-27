<?php

namespace App\Http\Controllers;

use PDF;
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class personsCabinetController extends Controller
{
    public function index()
    {

        $persTests = DB::table('pers_tests')
            ->where('pers_tests.pers_id', session('user_id'))
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

        $persEvent = DB::table('pers_events')
            ->leftjoin('events', 'events.id', 'pers_events.event_id')
            ->where('pers_events.pers_id', session('user_id'))
            ->select('pers_events.*', 'events.name', 'events.date_start', 'events.date_end')
            ->orderby('pers_events.id', 'desc')
            ->get();


        $persTests = [];
        $statusTest = [];
        $successTest = [];

        foreach ($persEvent as $pe)
        {
            $pers_test = DB::table('pers_tests')
                            ->where('pers_tests.pers_id', $pe->pers_id)
                            ->where('pers_tests.pers_event_id', $pe->id)
                            ->leftjoin('tests', 'tests.id', 'pers_tests.test_id')
                            ->select(
                                'pers_tests.id',
                                'tests.id as test_id',
                                'tests.discipline',
                                'pers_tests.status',
                                'pers_tests.start_time',
                                'pers_tests.end_time',
                                'pers_tests.test_ball_correct',
                                'pers_tests.last_active',
                                'tests.max_ball',
                                'tests.min_ball',
                                'tests.count_question',
                                'pers_tests.minuts_spent',
                                'pers_tests.pers_event_id'
                            )
                            ->orderby('pers_tests.id', 'desc')
                            ->get();
            $persTests += [$pe->id => $pers_test];

            foreach ($pers_test as $test) {
                $max_ball = 0;
                $max_quest = 0;
                $tc = DB::table('test_scatter')
                        ->where('test_id', $test->test_id)
                        ->orderBy('ball', 'asc')
                        ->get();
     
                $testScatter_success = true;
                if (count($tc) == 0) $testScatter_success = false;
                
                foreach ($tc as $tmp)
                {
                    $max_ball += $tmp->ball_count * $tmp->ball;
                    $max_quest += $tmp->ball_count;
    
                    $ttmp = DB::table('questions')->where('test_id', $test->test_id)->where('ball', $tmp->ball)->count();
                    if ($tmp->ball_count <= $ttmp) $testScatter_success= true;
                    else $testScatter_success = false;
                }
                if ($max_ball != $test->max_ball) $testScatter_success = false;
                if ($max_quest != $test->count_question) $testScatter_success = false;
    
                // добавить проверку на дату и время ивента этого теста
                $event = DB::table('pers_events')
                            ->leftjoin('events', 'events.id', 'pers_events.event_id')
                            ->where('pers_events.id', $test->pers_event_id)
                            ->select('events.*')
                            ->first();
    
                if((strtotime($event->date_start) <= time()) && (strtotime($event->date_end) >= time()) || $test->status == 2) $testScatter_success = true;
                else $testScatter_success = false;
    
                if ($testScatter_success) 
                {
                    switch ($test->status) {
                        case 0:
                            $status = "<span class='badge badge-primary'>Готов к прохождению</span>";
                            break;
                        case 1:
                            $status = "<span class='badge badge-warning'>В процессе</span>";
                            break;
                        case 2:
                            $status = $pt->test_ball_correct >= $pt->min_ball ? "<span class='badge badge-success'>Пройден</span>" : "<span class='badge badge-danger'>Не пройден</span>";
                            break;
                        case 3:
                            $status = "<span class='badge badge-danger'>Приостановлен</span>";
                            break;
                    }
                    $successTest += [
                        $test->test_id => "onclick='checkedRow($(this),".$pt->status.");testPersId=".$pt->id."'"
                    ];
                }
                else 
                {
                    $status = "<span class='badge badge-danger'>Тест не доступен</span>";
                    $successTest += [
                        $test->test_id => ""
                    ];
                }
                $statusTest += [
                    $test->test_id => $status
                ];
            }
    
        }

        $pers = DB::table('persons')->where('id', session('user_id'))->where('is_block', 'F')->first();
        return view('persons.cabinet', [
            'persEvents' => $persEvent,
            'persTests'  => $persTests,
            'person'     => $pers,
            'role'       => session('role_id'),
            'statusTest'    => $statusTest,
            'successTest'   => $successTest
        ]);
    }

    public function events(Request $request)
    {
        $events = DB::table('events')
                    ->whereNotIn('id', function($query) { 
                        $query->select(DB::raw('event_id'))
                        ->from('pers_events')
                        ->where('pers_events.pers_id',session('user_id')); 
                    }) 
                    ->where('is_active', 'T')
                    ->get();
        return view('persons.ajax.events',['events' => $events]);
    }

    public function regevent(Request $request)
    {
        $pers = DB::table('persons')->where('id', session('user_id'))->where('is_block', 'F')->first();
        $event = DB::table('events')->where('id', $request->eid)->first();
        $testsBac = DB::table('event_tests')
                    ->leftjoin('tests', 'tests.id', 'event_tests.test_id')
                    ->where('event_id', $request->eid)
                    ->where('tests.targetAudience_id', '1')
					->where('tests.status', '1')
                    ->select('tests.*')
                    ->get();
        $testsMC = DB::table('event_tests')
                    ->leftjoin('tests', 'tests.id', 'event_tests.test_id')
                    ->where('event_id', $request->eid)
                    ->where('tests.targetAudience_id', '5')
					->where('tests.status', '1')
                    ->select('tests.*')
                    ->get();
        $freeTime = [];
        $start = $event->date_start;
        $end = $event->date_end;
        $i = 0;
        while ($start <= $end) {
            $count_pers = DB::table('pers_tests')->where('id', $request->eid)->where('start_time', $start)->count();
            if ($count_pers < $event->count_place) { 
                $freeTime += [$i => [
                        'full' => $start,
                        'short'=> date('H:i',strtotime($start))
                    ]
                ]; 
                $i++;
            }
            $start = date('Y-m-d H:i:s', strtotime("+1 hours", strtotime($start)));
        }
        $event_date = date('d.m.Y', strtotime($event->date_start));
        return view('persons.regEvent',
            [
                'famil'         => $pers->famil,
                'name'          => $pers->name,
                'otch'          => $pers->otch,
                'place_study'   => $pers->study_place,
                'event_name'    => $event->name,
                'event_date'    => $event_date,
                'testsBac'      => $testsBac,
                'testsMC'       => $testsMC,
                'freeTime'      => $freeTime,
                'eid'           => $event->id
            ]
        );
    }

    public function savevent(Request $request)
    {
        $eid            = $request->eid;
        $place_study    = $request->place_study;
        $who            = $request->who;
        $first_test     = $request->first_test;
        $first_time     = $request->first_time;
        $second_test    = $request->second_test;
        $second_time    = $request->second_time;
        $is_hostel      = $request->is_hostel = 'T' ? 'T' : 'F';
        $first_testMC   = $request->first_testMC;
        $pe = DB::table('pers_events')->where('pers_id', session('user_id'))->count();

        if ($pe == 0) {

            DB::table('persons')->where('id', session('user_id'))->update(['study_place' => $place_study]);

            $peid = DB::table('pers_events')->insertGetId(
                [
                    'pers_id'   => session('user_id'),
                    'event_id'  => $eid,
                    'is_hostel' => $is_hostel
                ]
            );
            if ($who == 1)
            {
                if ($first_test != -1 && $first_time != -1)
                {
                    DB::table('pers_tests')->insert(
                        [
                            'pers_id'           => session('user_id'),
                            'test_id'           => $first_test,
                            'pers_event_id'     => $peid,
                            'start_time'        => $first_time
                        ]
                    );
                }
                if ($second_test != -1 && $second_time != -1)
                {
                    DB::table('pers_tests')->insert(
                        [
                            'pers_id'           => session('user_id'),
                            'test_id'           => $second_test,
                            'pers_event_id'     => $peid,
                            'start_time'        => $second_time
                        ]
                    );
                }
            }
            else 
            {
                if ($first_testMC != -1 && $first_time != -1)
                {
                    DB::table('pers_tests')->insert(
                        [
                            'pers_id'           => session('user_id'),
                            'test_id'           => $first_testMC,
                            'pers_test_evet'    => $peid,
                            'start_time'        => $first_time
                        ]
                    );
                }
            }
            return $peid;
        }
        else return -1;
    }
    function createPdf(Request $request)
    {
        $pers_event = DB::table('pers_events')->where('id', $request->peid)->first();
        $event = DB::table('events')->where('id', $pers_event->event_id)->first();
        $pers = DB::table('persons')->where('id', session('user_id'))->first();
        $tests = DB::table('pers_tests')
                    ->leftjoin('tests', 'tests.id', 'pers_tests.test_id')
                    ->where('pers_event_id', $request->peid)
                    ->select('pers_tests.*', 'tests.discipline')
                    ->get();
        
        $pdf = PDF::loadView('persons.pdfTemplate', [
                'famil' => $pers->famil,
                'name'  => $pers->name,
                'otch'  => $pers->otch,
                'PIN'   => $pers->PIN,
                'event_name'    => $event->name,
                'tests' => $tests
            ]
        );

        if ($request->status == 0) return $pdf->stream();
        else if ($request->status == 1) return $pdf->download('exam-sheet.pdf');
    }
    function sendRequest(Request $request)
    {
        $pers = DB::table('persons')->where('id', session('user_id'))->first();
        $fio = $pers->famil.' '.$pers->name.' '.$pers->otch;
        $text = $request->texta;
        $theme = $request->theme;
    
        Mail::send('persons.ajax.requestEmail', ['text' => $text, 'fio' => $fio, 'email' => $pers->email], function ($message) use ($pers, $theme) {
            $message->from('asu@ltsu.org', $pers->email);
            $message->to('asu@ltsu.org', 'Тех. поддержка')->subject($theme);
        });
        return 0;
    }
}
