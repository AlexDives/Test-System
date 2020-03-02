<?php

namespace App\Http\Controllers;

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
        foreach ($persEvent as $pe)
        {
            $persTests += [$pe->id => 
                    DB::table('pers_tests')
                    ->where('pers_tests.pers_id', $pe->pers_id)
                    ->where('pers_tests.pers_event_id', $pe->id)
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
        /*dump($persTests[1]);
        dd($persTests);*/
        $pers = DB::table('persons')->where('id', session('user_id'))->where('is_block', 'F')->first();
        return view('persons.cabinet', [
            'persEvents' => $persEvent,
            'persTests'  => $persTests,
            'person'     => $pers,
            'role'       => session('role_id')
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
                    ->select('tests.*')
                    ->get();
        $testsMC = DB::table('event_tests')
                    ->leftjoin('tests', 'tests.id', 'event_tests.test_id')
                    ->where('event_id', $request->eid)
                    ->where('tests.targetAudience_id', '5')
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
        return view('persons.regEvent',
            [
                'famil'         => $pers->famil,
                'name'          => $pers->name,
                'otch'          => $pers->otch,
                'event_name'    => $event->name,
                'testsBac'      => $testsBac,
                'testsMC'       => $testsMC,
                'freeTime'      => $freeTime,
                'eid'           => $event->id
            ]
        );
    }
}
