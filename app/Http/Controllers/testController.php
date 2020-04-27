<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function start(Request $request)
    {
        $test = DB::table('tests')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->leftjoin('pers_tests', 'pers_tests.test_id', 'tests.id')
            ->leftjoin('persons', 'persons.id', 'pers_tests.pers_id')
            ->select(
                'tests.*',
                'type_test.name as typeTestName',
                'persons.famil',
                'persons.name',
                'persons.otch',
                'pers_tests.start_time',
                'pers_tests.timeLeft'
            )
            ->where('pers_tests.id', $request->ptid)
            ->where('pers_tests.status', '<>', '2')
            ->first();
        if ($test != null) {
            $start_time = $test->start_time != null ? $test->start_time : date("Y-m-d H:i:s", time());
            $timeLeft = $test->timeLeft != 0 ? $test->timeLeft : $test->test_time;

            testController::generateTest($request->ptid, $test->id);
            session(['ptid' => $request->ptid]);

            DB::table('pers_tests')
                ->where('id', $request->ptid)
                ->update(
                    [
                        'start_time'    => $start_time,
                        'timeLeft'      => $timeLeft,
                        'minuts_spent' => 0,
                        'last_active'   => date("Y-m-d H:i:s", time()),
                        'status'        => 1
                    ]
                );

            $test_name = $test->typeTestName . ' - ' . $test->discipline;
            return view('testing.test', ['test' => $test, 'test_name' => $test_name, 'pers_test_id' => $request->ptid, 'timeLeft' => $timeLeft]);
        } else echo '<script>location.replace("/pers/cabinet");</script>';
        exit;
    }

    public function generateTest($ptid, $tid)
    {
        if (!DB::table('pers_test_details')->where('pers_test_id', $ptid)->exists()) {
            $test_scatter = DB::table('test_scatter')->where('test_id', $tid)->get();

            foreach ($test_scatter as $sc) {
                $questions = DB::table('questions')
                    ->leftjoin('pers_tests', 'pers_tests.test_id', 'questions.test_id')
                    ->leftjoin('question_details', 'question_details.quest_id', 'questions.id')
                    ->where('pers_tests.id', $ptid)
                    ->where('question_details.type', 'que')
                    ->where('questions.ball', $sc->ball)
                    ->select(
                        'questions.id',
                        'questions.ball',
                        'question_details.id as qtid',
                        'pers_tests.pers_id as pid',
                        'pers_tests.test_id as tid'
                    )
                    ->inRandomOrder()->limit($sc->ball_count)->get();
                foreach ($questions as $quest) {
                    DB::table('pers_test_details')
                        ->insert(
                            [
                                'pers_test_id'  =>  $ptid,
                                'pers_id'       =>  $quest->pid,
                                'test_id'       =>  $quest->tid,
                                'quest_id'      =>  $quest->id,
                                'quest_text_id' =>  $quest->qtid,
                                'quest_ball'    =>  $quest->ball
                            ]
                        );
                }
            }
        }
    }

    public function loadQuestList(Request $request)
    {
        $data = DB::table('pers_test_details')
            ->where('pers_test_id', session('ptid'))
            ->where('test_id', $request->tid)
            ->whereNull('answer_id')
            ->inRandomOrder()->get();
        return $data;
    }

    public function selectedQuest(Request $request)
    {
        $data = DB::table('question_details')
            ->select(DB::raw('id, quest_id, text, type'))
            ->where('quest_id', $request->id)
            ->inRandomOrder()->get();
        $newData = [];
        $i = 0;
        $ans = 1;
        foreach ($data as $d) {
            if ($d->type == 'que') $type = 'que';
            else {
                $type = 'ans' . $ans;
                $ans++;
            }

            $newData += [
                $i => [
                    'id'        => $d->id,
                    'quest_id'  => $d->quest_id,
                    'text'      => htmlspecialchars_decode($d->text),
                    'type'      => $type
                ]
            ];
            $i++;
        }

        return $newData;
    }

    public function confirmResponse(Request $request)
    {
        $pt = DB::table('pers_tests')
            ->where('id', session('ptid'))
            ->first();
        if ($pt != null) {
            $tid = DB::table('pers_test_details')
                ->where('pers_test_id', session('ptid'))
                ->where('test_id', $request->tid)
                ->first();
            if ($tid != null) {
                $qid = DB::table('pers_test_details')
                    ->where('pers_test_id', session('ptid'))
                    ->where('test_id', $request->tid)
                    ->where('quest_id', $request->qid)
                    ->first();
                if ($qid != null) {
                    $ansDetail = DB::table('question_details')->where('id', $request->ansid)->first();
                    if ($ansDetail != null) {
                        if ($ansDetail->type == 'cor') {
                            $quest = DB::table('questions')->where('id', $request->qid)->first();
                            $ansBall = $quest->ball;
                        } else $ansBall = 0;

                        $user = DB::table('users')->where('id', session('user_id'))->first();
                        DB::table('pers_test_details')
                            ->where('pers_test_id', session('ptid'))
                            ->where('test_id', $request->tid)
                            ->where('quest_id', $request->qid)
                            ->update(
                                [
                                    'answer_id'     => $request->ansid,
                                    'answer_ball'   => $ansBall,
                                    'answer_time'   => date("Y-m-d H:i:s", time()),
                                    'user_nick'     => $user->login,
                                    'aud_num'       => $user->aud_num,
                                    'comp_num'      => $user->comp_num
                                ]
                            );
                        return 0; // all right
                    } else return -4; // Не найден ansDetail, обратитесь к администратору! 
                } else return -3; // Не найден qid, обратитесь к администратору! 
            } else return -2; // Не найден tid, обратитесь к администратору! 
        } else return -1; // Не найден ptid, обратитесь к администратору!
    }
    public function timeLeft(Request $request)
    {
        $pt = DB::table('pers_tests')
            ->where('id', session('ptid'))
            ->first();

        if ($pt->timeLeft >= $request->leftMinutes) {
            DB::table('pers_tests')
                ->where('id', session('ptid'))
                ->update(['timeLeft' => $request->leftMinutes, 'minuts_spent' => $request->minuts_spent, 'last_active' => date("Y-m-d H:i:s", time())]);
            return 0;
        } else return -1;
    }

    public function result(Request $request)
    {
        $ptid = session('ptid');
        $test = DB::table('tests')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->leftjoin('pers_tests', 'pers_tests.test_id', 'tests.id')
            ->select(
                'tests.*',
                'type_test.name as typeTestName',
                'pers_tests.start_time',
                'pers_tests.timeLeft',
                'pers_tests.end_time',
                'pers_tests.minuts_spent'
            )
            ->where('pers_tests.id', $ptid)
            ->first();
        $countAllQuestion = $test->count_question;
        $maxBall = $test->max_ball;
        $maxTime = $test->test_time;
        $test_name = $test->typeTestName . ' - ' . $test->discipline;
        $countAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $ptid)
            ->whereNotNull('answer_id')
            ->count();
        $countTrueAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $ptid)
            ->where('answer_ball', ">", 0)
            ->whereNotNull('answer_id')
            ->count();

        $countFalseAnswer = DB::table('pers_test_details')
            ->where('pers_test_id', $ptid)
            ->where('answer_ball', "=", 0)
            ->whereNotNull('answer_id')
            ->count();

        $correctBall = DB::table('pers_test_details')
            ->where('pers_test_id', $ptid)
            ->whereNotNull('answer_id')
            ->sum('answer_ball');
        $startTime = $test->start_time;
        $endTime = $test->end_time != null ? $test->end_time : date("Y-m-d H:i:s", time());

        /*$timestampStart = strtotime($startTime);
        $timestampEnd = strtotime($endTime);
        $correntMinutes = round(($timestampEnd - $timestampStart) / 60);*/

        $correntMinutes = $test->minuts_spent;

        $resultTest = $correctBall > $test->min_ball ? true : false;

        DB::table('pers_tests')
            ->where('id', session('ptid'))
            ->update(
                [
                    'end_time'          => $endTime,
                    'timeLeft'          => 0,
                    'last_active'       => null,
                    'quest_count'       => $countAllQuestion,
                    'answer_count'      => $countAnswer,
                    'correct_count'     => $countTrueAnswer,
                    'test_ball_max'     => $maxBall,
                    'test_ball_correct' => $correctBall,
                    'status'            => 2
                ]
            );
        session('ptid', '');
        return view(
            'testing.result',
            [
                'test_name' => $test_name,
                'countAllQuestion' => $countAllQuestion,
                'maxBall' => $maxBall,
                'maxTime' => $maxTime,
                'countAnswer' => $countAnswer,
                'countTrueAnswer' => $countTrueAnswer,
                'countFalseAnswer' => $countFalseAnswer,
                'correctBall' => $correctBall,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'correntMinutes' => $correntMinutes,
                'resultTest' => $resultTest,
                'role' => session('role_id'),
                'ptid'  => $ptid
            ]
        );
    }

    function speedTest(Request $request)
    {
        if ((session('role_id') == 1) || (session('role_id') == 2)) {
            $pt = DB::table('pers_tests')
                ->where('id', session('ptid'))
                ->first();
            if ($pt != null) {
                $tid = DB::table('pers_test_details')
                    ->where('pers_test_id', session('ptid'))
                    ->where('test_id', $request->tid)
                    ->first();
                if ($tid != null) {
                    $pers_test_details = DB::table('pers_test_details')
                        ->where('pers_test_id', session('ptid'))
                        ->get();
                    if ($pers_test_details != null) {
                        foreach ($pers_test_details as $ptd) {
                            $ansDetail = DB::table('question_details')->where('quest_id', $ptd->quest_id)->where('type', 'cor')->first();

                            if ($ansDetail != null) {
                                if ($ansDetail->type == 'cor') {
                                    $quest = DB::table('questions')->where('id', $ptd->quest_id)->first();
                                    $ansBall = $quest->ball;
                                } else $ansBall = 0;

                                $user = DB::table('users')->where('id', session('user_id'))->first();
                                DB::table('pers_test_details')
                                    ->where('pers_test_id', session('ptid'))
                                    ->where('test_id', $request->tid)
                                    ->where('quest_id', $ptd->quest_id)
                                    ->update(
                                        [
                                            'answer_id'     => $ansDetail->id,
                                            'answer_ball'   => $ansBall,
                                            'answer_time'   => date("Y-m-d H:i:s", time()),
                                            'user_nick'     => $user->login,
                                            'aud_num'       => $user->aud_num,
                                            'comp_num'      => $user->comp_num
                                        ]
                                    );
                                return 0; // all right
                            } else return -4; // Не найден ansDetail, обратитесь к администратору! 
                        }
                    } else return -3; // Не найден qid, обратитесь к администратору! 
                } else return -2; // Не найден tid, обратитесь к администратору! 
            } else return -1; // Не найден ptid, обратитесь к администратору!
        }
    }
}
