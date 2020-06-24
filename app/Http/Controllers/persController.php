<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class persController extends Controller
{
    public function persList(Request $request)
    {
        $personList = DB::table('persons')->where('is_block', 'F')->where('pers_type', '<>', 'g')->paginate(10);
        return view('testing.pers.list', ['persList' => $personList, 'role_id' => session('role_id')]);
    }
    
    public function searchPers(Request $request)
    {
        $personList = DB::table('persons')
                        ->where('is_block', 'F')
                        ->where('famil', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('otch', 'like', '%' . $request->search . '%')
                        ->orWhere('pin', 'like', '%' . $request->search . '%')
                        ->orWhere('login', 'like', '%' . $request->search . '%')
                        ->paginate(10);
        return view('testing.ajax.persTable', ['persList' => $personList, 'role_id' => session('role_id')]);
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
        
        $statusTest = [];
        $successTest = [];
    
        foreach ($persTests as $test) {
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
                        $status = $test->test_ball_correct >= $test->min_ball ? "<span class='badge badge-success'>Пройден</span>" : "<span class='badge badge-danger'>Не пройден</span>";
                        break;
                    case 3:
                        $status = "<span class='badge badge-danger'>Приостановлен</span>";
                        break;
                }
                $successTest += [
                    $test->id => "onclick='checkedRow($(this),".$test->status.");testPersId=".$test->id."'"
                ];
            }
            else 
            {
                $status = "<span class='badge badge-danger'>Тест не доступен</span>";
                $successTest += [
                    $test->id => ""
                ];
            }
            $statusTest += [
                $test->id => $status
            ];
        }


        $pers = DB::table('persons')->where('id', session('pers_id'))->first();

        return view('testing.pers.cabinet', [
            'persTests'     => $persTests,
            'person'        => $pers,
            'role'          => session('role_id'),
            'statusTest'    => $statusTest,
            'successTest'   => $successTest
        ]);
    }
}
