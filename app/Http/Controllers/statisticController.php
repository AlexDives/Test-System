<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DateTime;

class statisticController extends Controller
{
    public function main(Request $request)
    {
        $tests = DB::table('tests')->where('tests.id', $request->id)
                ->leftJoin('target_Audience', 'target_Audience.id', '=', 'tests.targetAudience_id')
                ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                ->select(
                    'tests.*',
                    'target_Audience.name as targetAudienceName',
                    'type_test.name as typeTestName'
                )->first();
        $testName = $tests->discipline.' | '.$tests->typeTestName.' | '.$tests->targetAudienceName;
        $tid = $tests->id;
        return view('editor.statistic', ['testName' => $testName,
                                         'tid' => $tid]);
    }

    public function loadStats(Request $request)
    {
        if ($request->tid > 0)
        {
            if($request->typeStat == 1) {
                $countPassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->count();
                $countTruePassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '>=', 24)->count();
                $countFalsePassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<', 24)->count();

                $maxBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->max('test_ball_correct');
                $minBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->min('test_ball_correct');
                $avgBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->avg('test_ball_correct');
                
                $ball90100 = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 100)->where('test_ball_correct', '>=', 90)->count();
                $ball7089  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 89)->where('test_ball_correct', '>=', 70)->count();
                $ball5069  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 69)->where('test_ball_correct', '>=', 50)->count();
                $ball2449  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 49)->where('test_ball_correct', '>=', 24)->count();
                $ball0023  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 23)->where('test_ball_correct', '>=', 0)->count(); 
            }
            /*else if($request->typeStat == 2) {
                $today = $now->today()->toDateString();
                $last_month = $now->subDays(30)->toDateString();

                $countPassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $countTruePassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '>=', 24)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $countFalsePassage = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<', 24)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();

                $maxBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->whereBetween('pers_tests.end_time',[$last_month, $today])->max('test_ball_correct');
                $minBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->whereBetween('pers_tests.end_time',[$last_month, $today])->min('test_ball_correct');
                $avgBall = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->whereBetween('pers_tests.end_time',[$last_month, $today])->avg('test_ball_correct');
                
                $ball90100 = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 100)->where('test_ball_correct', '>=', 90)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $ball7089  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 89)->where('test_ball_correct', '>=', 70)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $ball5069  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 69)->where('test_ball_correct', '>=', 50)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $ball2449  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 49)->where('test_ball_correct', '>=', 24)->whereBetween('pers_tests.end_time',[$last_month, $today])->count();
                $ball0023  = db::table('pers_tests')->where('test_id', $request->tid)->where('status', 2)->where('test_ball_correct', '<=', 23)->where('test_ball_correct', '>=', 0)->whereBetween('pers_tests.end_time',[$last_month, $today])->count(); 
            }*/
            $countPassage = $countPassage != null ? $countPassage : 0;
            $countTruePassage = $countTruePassage != null ? $countTruePassage : 0;
            $countFalsePassage = $countFalsePassage != null ? $countFalsePassage : 0;
            $maxBall = $maxBall != null ? $maxBall : 0;
            $minBall = $minBall != null ? $minBall : 0;
            $avgBall = $avgBall != null ? $avgBall : 0;
            $ball90100 = $ball90100 != null ? $ball90100 : 0;
            $ball7089 = $ball7089 != null ? $ball7089 : 0;
            $ball5069 = $ball5069 != null ? $ball5069 : 0;
            $ball2449 = $ball2449 != null ? $ball2449 : 0;
            $ball0023 = $ball0023 != null ? $ball0023 : 0;

            /*dump($countPassage);
            dump($countTruePassage);
            dump($countFalsePassage);
            dump($maxBall);
            dump($minBall);
            dump($avgBall);
            dump($ball90100);
            dump($ball7089);
            dump($ball5069);
            dump($ball2449);
            dump($ball0023);
            */
            $data = ['countPassage' => $countPassage, 'countTruePassage' => $countTruePassage, 'countFalsePassage' => $countFalsePassage,
                     'maxBall' => $maxBall, 'minBall' => $minBall, 'avgBall' => $avgBall, 'ball90100' => $ball90100,
                     'ball7089' => $ball7089, 'ball5069' => $ball5069, 'ball2449' => $ball2449, 'ball0023' => $ball0023];

            return $data;
        }
    }
}