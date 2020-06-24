<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class giaController extends Controller
{
    static $eid = 5; // id - мероприятия для ГИА

    public function index(Request $request){
        
        $events = DB::table('events')
                    ->where('id', giaController::$eid)
                    ->where('date_start', '<=', date('Y-m-d H:i:s', time()))
                    ->where('date_end', '>=', date('Y-m-d H:i:s', time()))
                    ->first();
        return view('GIA.index', [ 'events' => $events ]);
    }

    function registration(Request $request)
    {
        // ваш секретный ключ
        $secret = '6LfwCtUUAAAAAJiz-peMncLSTkbHBLAPqyxomlF5';

        $captcha = $request->captcha;
        $verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha), true);
        if ($verify['success']) {
            $famil      = $request->famil;
            $name       = $request->name;
            $otch       = $request->otch;
            $group      = mb_strtoupper($request->group, 'UTF-8');

            $c = DB::table('persons')->where('famil', $famil)->where('name', $name)->where('otch', $otch)->where('study_place', $group)->where('user_hash', '-')->where('pers_type', 'g')->count();

            if ($c == 0) {
                $pid = db::table('persons')->insertGetId(
                            [
                                'famil'         => $famil,
                                'name'          => $name,
                                'otch'          => $otch,
                                'study_place'   => $group,
                                'user_hash'     => "-",
                                'pers_type'     => "g"
                            ]
                        );
                $ptid = giaController::regevent($pid);
                session(['person_id' => $pid, 'role_id' => 5]);
                return $ptid;
            }
            else return -1;
        } else if (!$verify['success']) {
            return 1;
        }
    }
    static function regevent($pid)
    {
        // $eid         = 5;   // id - мероприятия для ГИА
        $tid            = 196; // id - теста для ГИА
        $first_time     = date('Y-m-d H:i:s', time());

        $pe = DB::table('pers_events')->where('pers_id', $pid)->where('event_id', giaController::$eid)->count();

        if ($pe == 0) {
            $peid = DB::table('pers_events')->insertGetId(
                [
                    'pers_id'   => $pid,
                    'event_id'  => giaController::$eid
                ]
            );
            $ptid = DB::table('pers_tests')->insertGetId(
                [
                    'pers_id'           => $pid,
                    'test_id'           => $tid,
                    'pers_event_id'     => $peid,
                    'start_time'        => $first_time
                ]
            );

            return $ptid;
        }
        else return -1;
    }

    public function result(Request $request)
    {
        $groups = DB::table('persons')->where('pers_type', 'g')->distinct()->orderby('study_place', 'asc')->select('study_place')->get();
        return view('GIA.result', [
            'groups'    => $groups,
            'role'      => session('role_id')
        ]);
    }

    public function get_list(Request $request)
    {
        if ($request->gid == -1) {
            $persons = DB::table('persons as p')
                        ->leftjoin('pers_tests as pt', 'pt.pers_id', 'p.id')
                        ->leftjoin('tests as t', 't.id', 'pt.test_id')
                        ->where('p.pers_type', 'g')
                        ->select(
                            'p.famil',
                            'p.name',
                            'p.otch',
                            'p.study_place',
                            'p.id',
                            'pt.id as ptid',
                            'pt.status',
                            'pt.start_time',
                            'pt.test_ball_correct',
                            'pt.minuts_spent',
                            't.min_ball'
                        )
                        ->orderby('p.study_place', 'asc')
                        ->orderby('p.famil', 'asc')
                        ->get();
        }
        else 
        {
            $persons = DB::table('persons as p')
                        ->leftjoin('pers_tests as pt', 'pt.pers_id', 'p.id')
                        ->leftjoin('tests as t', 't.id', 'pt.test_id')
                        ->where('p.pers_type', 'g')
                        ->where('p.study_place', $request->gid)
                        ->select(
                            'p.famil',
                            'p.name',
                            'p.otch',
                            'p.study_place',
                            'p.id',
                            'pt.id as ptid',
                            'pt.status',
                            'pt.start_time',
                            'pt.test_ball_correct',
                            'pt.minuts_spent',
                            't.min_ball'
                        )
                        ->orderby('p.study_place', 'asc')
                        ->orderby('p.famil', 'asc')
                        ->get();
        }
        $doublePers = [];
        foreach ($persons as $p)
        {
            $c = DB::table('persons')->where([['famil','=', $p->famil], ['name', '=', $p->name], ['otch', '=', $p->otch], ['pers_type', '=', 'g'], ['study_place', '=', $p->study_place], ['id', '<>', $p->id]])->count();
            $doublePers += [
                $p->id => $c == 0 ? false : true
            ];
        }
        return view('GIA.listGroup', [
            'doublePers'=> $doublePers,
            'pers'      => $persons,
            'role'      => session('role_id')
        ]);
    }

    public function printResultPers(Request $request)
    {
        $test = DB::table('tests')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->leftjoin('pers_tests', 'pers_tests.test_id', 'tests.id')
            ->leftjoin('persons', 'persons.id', 'pers_tests.pers_id')
            ->select(
                'tests.*',
                'type_test.name as typeTestName',
                'pers_tests.start_time',
                'pers_tests.timeLeft',
                'pers_tests.end_time',
                'persons.famil',
                'persons.name',
                'persons.otch',
                'persons.pers_type'
            )
            ->where('pers_tests.id', $request->ptid)
            ->first();
        $countAllQuestion = $test->count_question;
        $maxBall = $test->max_ball;
        $test_name = $test->typeTestName . ' - ' . $test->discipline;
        $fio = $test->famil . ' ' . $test->name . ' ' . $test->otch;

        $countAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $request->ptid)
            ->whereNotNull('answer_id')
            ->count();
        $countTrueAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $request->ptid)
            ->where('answer_ball', ">", 0)
            ->whereNotNull('answer_id')
            ->count();

        $countFalseAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $request->ptid)
            ->where('answer_ball', "=", 0)
            ->whereNotNull('answer_id')
            ->count();

        $correctBall = DB::table('pers_test_details')
            ->where('pers_test_id', $request->ptid)
            ->whereNotNull('answer_id')
            ->sum('answer_ball');

        $pdf = PDF::loadView(
            'GIA.reports.printResultPers',
            [
                'test_name'         => $test_name,
                'countAllQuestion'  => $countAllQuestion,
                'maxBall'           => $maxBall,
                'countAnswer'       => $countAnswer,
                'countTrueAnswer'   => $countTrueAnswer,
                'countFalseAnswer'  => $countFalseAnswer,
                'correctBall'       => $correctBall,
                'fio'               => $fio,
                'test_type'         => $test->typeTestName
            ]
        );
        return $pdf->stream();
    }

    public function printResultAll(Request $request)
    {
        $persons = [];
        $groups  = null;
        if ($request->gid == -1) {
            $groups = DB::table('persons')->where('pers_type', 'g')->distinct()->orderby('study_place', 'asc')->select('study_place')->get();
            foreach ($groups as $group) {
                $persons += [
                    $group->study_place => DB::table('persons as p')
                        ->leftjoin('pers_tests as pt', 'pt.pers_id', 'p.id')
                        ->leftjoin('tests as t', 't.id', 'pt.test_id')
                        ->where('p.pers_type', 'g')
                        ->where('p.study_place', $group->study_place)
                        ->select(
                            'p.famil',
                            'p.name',
                            'p.otch',
                            'p.study_place',
                            'p.id',
                            'pt.id as ptid',
                            'pt.status',
                            'pt.start_time',
                            'pt.test_ball_correct',
                            'pt.minuts_spent',
                            't.min_ball'
                        )
                        ->orderby('p.famil', 'asc')
                        ->get()
                ];
            }
        }
        else 
        {
            $groups = DB::table('persons')->where('study_place', $request->gid)->distinct()->orderby('study_place', 'asc')->select('study_place')->get();
            foreach ($groups as $group) {
                $persons += [
                    $group->study_place => DB::table('persons as p')
                        ->leftjoin('pers_tests as pt', 'pt.pers_id', 'p.id')
                        ->leftjoin('tests as t', 't.id', 'pt.test_id')
                        ->where('p.pers_type', 'g')
                        ->where('p.study_place', $group->study_place)
                        ->select(
                            'p.famil',
                            'p.name',
                            'p.otch',
                            'p.study_place',
                            'p.id',
                            'pt.id as ptid',
                            'pt.status',
                            'pt.start_time',
                            'pt.test_ball_correct',
                            'pt.minuts_spent',
                            't.min_ball'
                        )
                        ->orderby('p.famil', 'asc')
                        ->get()
                ];
            }
        }
        $pdf = PDF::loadView(
            'GIA.reports.printResultAll',
            [
                'groups'        => $groups,
                'persons'       => $persons
            ]
        );
        return $pdf->stream();
        /*return view('GIA.reports.printResultAll',
            [
                'groups'        => $groups,
                'persons'       => $persons
            ]
        );*/
    }
}
