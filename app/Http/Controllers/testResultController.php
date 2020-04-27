<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class testResultController extends Controller
{
    public function short(Request $request)
    {
<<<<<<< HEAD
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

        if ($test->pers_type != "t")
        {

        }
        else 
        {
            $minid = "";
            $facult = "";
            $spec = "";
            $fo = "";
            $stlevel = "";
        }

        $pdf = PDF::loadView(
            'testing.testResults.short',
            [
                'test_name'         => $test_name,
                'countAllQuestion'  => $countAllQuestion,
                'maxBall'           => $maxBall,
                'countAnswer'       => $countAnswer,
                'countTrueAnswer'   => $countTrueAnswer,
                'countFalseAnswer'  => $countFalseAnswer,
                'correctBall'       => $correctBall,
                'fio'               => $fio,
                'minid'             => $minid,
                'facult'            => $facult,
                'spec'              => $spec,
                'fo'                => $fo,
                'stlevel'           => $stlevel,
                'test_type'         => $test->typeTestName
            ]
        );
        return $pdf->stream();
        /*if ($request->t == 'pdf')
            
        else
            return view(
                'testing.testResults.short',
                [
                    'test_name'         => $test_name,
                    'countAllQuestion'  => $countAllQuestion,
                    'maxBall'           => $maxBall,
                    'countAnswer'       => $countAnswer,
                    'countTrueAnswer'   => $countTrueAnswer,
                    'countFalseAnswer'  => $countFalseAnswer,
                    'correctBall'       => $correctBall,
                    'fio'               => $fio,
                    'minid'             => $minid,
                    'facult'            => $facult,
                    'spec'              => $spec,
                    'fo'                => $fo,
                    'stlevel'           => $stlevel
                ]
            );*/
=======
        $pdf = PDF::loadView('testing.testResults.short');
        if ($request->t == 'pdf')
            return $pdf->stream();
        else
            return view('testing.testResults.short');
>>>>>>> af6d712a7431e3b4be6cbde0e50d93227f613f2c
    }

    public function full(Request $request)
    {
        return view('testing.testResults.full');
    }
}
