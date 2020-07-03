<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class testResultController extends Controller
{
    public function short(Request $request)
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

        if ($test->pers_type == "a")
        {
            $minid = "";
            $facult = "";
            $spec = "";
            $fo = "";
            $stlevel = "";
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
    }

    public function full(Request $request)
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

        $fullTest = DB::table('pers_test_details as ptd')
            ->where('ptd.pers_test_id', $request->ptid)
            ->whereNotNull('ptd.answer_id')
            ->get();
        
        $fullResult = [];
        $quest_num = 0;
        foreach ($fullTest as $ft)
        {
            $quest_num++;
            if ($ft->answer_ball == 0)
            {
                $quest_text = DB::table('question_details')->where('id', $ft->quest_text_id)->first()->text;
                $answer_text = DB::table('question_details')->where('id', $ft->answer_id)->first()->text; 
                $cor_text = DB::table('question_details')->where('quest_id', $ft->quest_id)->where('type', 'cor')->first()->text;
                $quest_ball = $ft->quest_ball;
                $fullResult += [ 
                    $quest_num => [
                        'quest_num'     => $quest_num,
                        'quest_text'    => $quest_text,
                        'answer_text'   => $answer_text,
                        'cor_text'      => $cor_text,
                        'quest_ball'    => $quest_ball
                    ]  
                ];
            }
        }
        
        return view('testing.testResults.full',
        [
            'test_name'         => $test_name,
            'countAllQuestion'  => $countAllQuestion,
            'maxBall'           => $maxBall,
            'countAnswer'       => $countAnswer,
            'countTrueAnswer'   => $countTrueAnswer,
            'countFalseAnswer'  => $countFalseAnswer,
            'correctBall'       => $correctBall,
            'fio'               => $fio,
            'test_type'         => $test->typeTestName,
            'fullResult'        => $fullResult
        ]);

        /*$pdf = PDF::loadView(
            'testing.testResults.full',
            [
                'test_name'         => $test_name,
                'countAllQuestion'  => $countAllQuestion,
                'maxBall'           => $maxBall,
                'countAnswer'       => $countAnswer,
                'countTrueAnswer'   => $countTrueAnswer,
                'countFalseAnswer'  => $countFalseAnswer,
                'correctBall'       => $correctBall,
                'fio'               => $fio,
                'test_type'         => $test->typeTestName,
                'fullResult'        => $fullResult
            ]
        );
        return $pdf->stream();*/
    }
}
