<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class editorController extends Controller
{

    public function main(Request $request)
    {
        return editorController::loadTest();
    }

    public function loadTest()
    {
        $testScatter = [];

        if (session('role_id') == 1 || session('role_id') == 2) {
            $tests = DB::table('tests')->distinct()
                ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                ->select(
                    'tests.*',
                    'target_audience.name as targetAudienceName',
                    'type_test.name as typeTestName',
                    'users.famil',
                    'users.name',
                    'users.otch'
                )
                ->orderBy('id', "desc")
                ->get();
            $countTest = DB::table('tests')->count();
        } else {
            $tests = DB::table('tests')->distinct()
                ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                ->select(
                    'tests.*',
                    'target_audience.name as targetAudienceName',
                    'type_test.name as typeTestName',
                    'users.famil',
                    'users.name',
                    'users.otch'
                )
                ->where('test_editors.user_id', session('user_id'))
                ->where('status', 1)
                ->get();
            $countTest = DB::table('tests')->distinct()
                ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                ->where('test_editors.user_id', session('user_id'))
                ->where('status', 1)
                ->count();
        }

        $success = [];
        foreach ($tests as $test) {
            $tc = DB::table('test_scatter')
                    ->where('test_id', $test->id)
                    ->orderBy('ball', 'asc')
                    ->get();
            $testScatter += [$test->id => $tc];
            
            if ($tc == null) $success += [$test->id => 'false'];
            else
                foreach ($tc as $tmp)
                {
                    $ttmp = DB::table('questions')->where('test_id', $test->id)->where('ball', $tmp->ball)->count();

                    if ($ttmp <= $tmp->ball_count)
                    {
                        break;
                        $success += [$test->id => 'false'];
                    }
                    else {
                        $success += [$test->id => 'true'];
                    }
                }
        }
        return view('editor.main', [
            'role_id'       => session('role_id'),
            'user_id'       => session('user_id'),
            'tests'         => $tests,
            'testScatter'   => $testScatter,
            'testCount'     => $countTest,
            'successTest'   => $success
        ]);
    }

    public function info(Request $request)
    {
        $test = DB::table('tests')
            ->leftJoin('users', 'users.id', '=', 'tests.user_id')
            ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->select(
                'tests.*',
                'target_audience.name as targetAudienceName',
                'type_test.name as typeTestName',
                'target_audience.id as targetAudienceid',
                'type_test.id as typeTestid'
            )
            ->where('tests.id', $request->id)
            ->get();

        $testScatter = editorController::loadTestScatter($request->id);
        $testAssembleds = editorController::loadTestAssembleds($request->id);
        $typeTest = editorController::loadTypeTest();
        $targetAudience = editorController::loadTargetAudience();
        $testList = editorController::loadTestList($request->id);

        return view('editor.info', [
            'role_id'       => session('role_id'),
            'user_id'       => session('user_id'),
            'test'          => $test,
            'testScatter'   => $testScatter,
            'testAssembleds' => $testAssembleds,
            'typeTest'      => $typeTest,
            'targetAudience' => $targetAudience,
            'testList'      => $testList
        ]);
    }

    public function saveTestInfo(Request $request)
    {
        $targetAudience = htmlspecialchars($request->targetAudience);
        $targetAudience = stripslashes($targetAudience);
        $typeTest = htmlspecialchars($request->typeTest);
        $typeTest = stripslashes($typeTest);
        $discipline = htmlspecialchars($request->discipline);
        $discipline = stripslashes($discipline);
        $validator = htmlspecialchars($request->validator);
        $validator = stripslashes($validator);
        $min_ball = htmlspecialchars($request->min_ball);
        $min_ball = stripslashes($min_ball);
        $max_ball = htmlspecialchars($request->max_ball);
        $max_ball = stripslashes($max_ball);
        $test_time = htmlspecialchars($request->test_time);
        $test_time = stripslashes($test_time);
        $count_question = htmlspecialchars($request->count_question);
        $count_question = stripslashes($count_question);

        if ($request->tid <= 0) {

            $id = DB::table('tests')->insertGetId(
                [
                    'user_id' => session('user_id'), 'targetAudience_id' => $targetAudience, 'type_id' => $typeTest,
                    'discipline' => $discipline, 'validator' => $validator, 'min_ball' => $min_ball,
                    'max_ball' => $max_ball,  'test_time' => $test_time, 'count_question' => $count_question, 'status' => 1
                ]
            );
            DB::table('test_editors')->insert(['user_id' => session('user_id'), 'test_id' => $id, 'is_owner' => 'T']);

            $i = 1;
            foreach ($request->scatterBall as $sBall) {
                $scatterBallCount = $request->scatterBallCount[$i];
                $scatterBallCount = htmlspecialchars($scatterBallCount);
                $scatterBallCount = stripslashes($scatterBallCount);
                if ($sBall != null && $scatterBallCount != null) {
                    DB::table('test_scatter')->insert(['test_id' => $id, 'ball' => $sBall, 'ball_count' => $scatterBallCount]);
                }
                $i++;
            }

            return $id;
        } else if ($request->tid > 0) {
            DB::table('tests')->where('id', $request->tid)->update(
                [
                    'targetAudience_id' => $targetAudience, 'type_id' => $typeTest,
                    'discipline' => $discipline, 'validator' => $validator, 'min_ball' => $min_ball,
                    'max_ball' => $max_ball,  'test_time' => $test_time, 'count_question' => $count_question
                ]
            );

            $i = 1;

            foreach ($request->scatterId as $sId) {
                $scatterBall = $request->scatterBall[$i];
                $scatterBall = htmlspecialchars($scatterBall);
                $scatterBall = stripslashes($scatterBall);
                $scatterBallCount = $request->scatterBallCount[$i];
                $scatterBallCount = htmlspecialchars($scatterBallCount);
                $scatterBallCount = stripslashes($scatterBallCount);
                if ($sId != null) {
                    if ($scatterBall != null && $scatterBallCount != null) {
                        DB::table('test_scatter')->where('id', $sId)->update(
                            ['ball' => $scatterBall, 'ball_count' => $scatterBallCount]
                        );
                    } else {
                        DB::table('test_scatter')->where('id', $sId)->delete();
                    }
                } else {
                    if ($scatterBall != null && $scatterBallCount != null) {
                        DB::table('test_scatter')->insert(['test_id' => $request->tid, 'ball' => $scatterBall, 'ball_count' => $scatterBallCount]);
                    }
                }
                $i++;
            }

            return $request->tid;
        }
        return -1;
    }

    public function newTest(Request $request)
    {
        $typeTest = editorController::loadTypeTest();
        $targetAudience = editorController::loadTargetAudience();
        $testList = editorController::loadTestList($request->id);
        return view('editor.info', [
            'role_id'       => session('role_id'),
            'user_id'       => session('user_id'),
            'typeTest'      => $typeTest,
            'targetAudience' => $targetAudience,
            'testList'      => $testList
        ]);
    }

    public function deleteTest(Request $request)
    {
        DB::table('tests')->where('id', $request->id)->update(['status' => 2]);
        return redirect('/editor');
    }

    public function rollBackTest(Request $request)
    {
        DB::table('tests')->where('id', $request->id)->update(['status' => 1]);
        return redirect('/editor');
    }

    private function loadTypeTest()
    {
        return DB::table('type_test')->get();
    }

    private function loadTestAssembleds($id)
    {
        return DB::table('test_assembleds')
            ->where('main_test_id', $id)
            ->get();
    }

    private function loadTestScatter($id)
    {
        return DB::table('test_scatter')
            ->where('test_id', $id)
            ->orderBy('ball', 'asc')
            ->get();
    }

    private function loadTargetAudience()
    {
        return DB::table('target_audience')->get();
    }

    private function loadTestList($id)
    {
        return DB::table('tests')
            ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
            ->select('tests.*', 'target_audience.name as targetAudienceName', 'type_test.name as typeTestName')
            ->where('tests.id', '<>', $id)->where('test_editors.user_id', session('user_id'))
            ->get();
    }

    public function searchTest(Request $request)
    {
        $testScatter = [];
        if ($request->searchTestText != '') {
            if (session('role_id') == 1 || session('role_id') == 2) {
                $tests = DB::table('tests')->distinct()
                    ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                    ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                    ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                    ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                    ->select(
                        'tests.*',
                        'target_audience.name as targetAudienceName',
                        'type_test.name as typeTestName',
                        'users.famil',
                        'users.name',
                        'users.otch'
                    )
                    ->where('tests.discipline', 'like', '%' . $request->searchTestText . '%')
                    ->orderBy('id', "desc")
                    ->get();
            } else {
                $tests = DB::table('tests')->distinct()
                    ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                    ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                    ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                    ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                    ->select(
                        'tests.*',
                        'target_audience.name as targetAudienceName',
                        'type_test.name as typeTestName',
                        'users.famil',
                        'users.name',
                        'users.otch'
                    )
                    ->where('test_editors.user_id', session('user_id'))
                    ->where('tests.discipline', 'like', '%' . $request->searchTestText . '%')
                    ->where('status', 1)
                    ->get();
            }
        } else {
            if (session('role_id') == 1 || session('role_id') == 2) {
                $tests = DB::table('tests')->distinct()
                    ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                    ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                    ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                    ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                    ->select(
                        'tests.*',
                        'target_audience.name as targetAudienceName',
                        'type_test.name as typeTestName',
                        'users.famil',
                        'users.name',
                        'users.otch'
                    )
                    ->orderBy('id', "desc")
                    ->get();
            } else {
                $tests = DB::table('tests')->distinct()
                    ->leftJoin('users', 'users.id', '=', 'tests.user_id')
                    ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
                    ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
                    ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
                    ->select(
                        'tests.*',
                        'target_audience.name as targetAudienceName',
                        'type_test.name as typeTestName',
                        'users.famil',
                        'users.name',
                        'users.otch'
                    )
                    ->where('test_editors.user_id', session('user_id'))
                    ->where('status', 1)
                    ->get();
            }
        }
        $success = [];
        
        foreach ($tests as $test) {
            $max_ball = 0;
            $max_quest = 0;
            $tc = DB::table('test_scatter')
                    ->where('test_id', $test->id)
                    ->orderBy('ball', 'asc')
                    ->get();
            $testScatter += [$test->id => $tc]; // добавить проверку на макс балл и количество отображаемых вопросов

            $testScatter_success = 'true';
            if (count($tc) == 0) $testScatter_success = 'false';
            
            foreach ($tc as $tmp)
            {
                $max_ball += $tmp->ball_count * $tmp->ball;
                $max_quest += $tmp->ball_count;

                $ttmp = DB::table('questions')->where('test_id', $test->id)->where('ball', $tmp->ball)->count();
                if ($tmp->ball_count <= $ttmp) $testScatter_success= 'true';
                else {
                    $testScatter_success = 'false';
                break;
                }
            }
            if ($max_ball != $test->max_ball) $testScatter_success = 'false';
            if ($max_quest != $test->count_question) $testScatter_success = 'false';
            $success += [$test->id => $testScatter_success];
        }
        return view('editor.ajax.testList', ['tests' => $tests, 'testScatter'   => $testScatter, 'successTest' => $success, 'role_id' => session('role_id')]);
    }

    public function questions(Request $request)
    {
        $tests = DB::table('tests')
            ->leftJoin('users', 'users.id', '=', 'tests.user_id')
            ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->select(
                'tests.*',
                'target_audience.name as targetAudienceName',
                'type_test.name as typeTestName'
            )
            ->where('tests.id', $request->id)
            ->first();
        $test_name = $tests->discipline . ' | ' . $tests->typeTestName . ' | ' . $tests->targetAudienceName;
        return view('editor.questions', [
            'test_id'   => $request->id,
            'test_name' => $test_name,
            'role_id'   => session('role_id')
        ]);
    }

    public function saveQuestions(Request $request)
    {
        $qid = -1;

        $ballQuest = htmlspecialchars($request->ballQuest);
        $ballQuest = stripslashes($ballQuest);
        $textQuest = htmlspecialchars($request->textQuest);
        $textQuest = stripslashes($textQuest);
        $answerTrueQuest = htmlspecialchars($request->answerTrueQuest);
        $answerTrueQuest = stripslashes($answerTrueQuest);
        $answerFalse1Quest = htmlspecialchars($request->answerFalse1Quest);
        $answerFalse1Quest = stripslashes($answerFalse1Quest);
        $answerFalse2Quest = htmlspecialchars($request->answerFalse2Quest);
        $answerFalse2Quest = stripslashes($answerFalse2Quest);
        $answerFalse3Quest = htmlspecialchars($request->answerFalse3Quest);
        $answerFalse3Quest = stripslashes($answerFalse3Quest);

        if ($request->idQuest <= 0) {

            $tid = htmlspecialchars($request->tid);
            $tid = stripslashes($tid);

            $qid = DB::table('questions')->insertGetId(
                ['test_id' => $tid, 'ball' => $ballQuest]
            );

            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => $textQuest, 'type' => 'que']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => $answerTrueQuest, 'type' => 'cor']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => $answerFalse1Quest, 'type' => 'dis1']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => $answerFalse2Quest, 'type' => 'dis2']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => $answerFalse3Quest, 'type' => 'dis3']
            );
        } else {
            $qid = htmlspecialchars($request->idQuest);
            $qid = stripslashes($qid);
            DB::table('questions')
                ->where('id', $qid)
                ->update(
                    ['ball' => $ballQuest]
                );

            DB::table('question_details')
                ->where('quest_id', $qid)
                ->where('type', 'que')
                ->update(
                    ['text' => $textQuest]
                );
            DB::table('question_details')
                ->where('quest_id', $qid)
                ->where('type', 'cor')
                ->update(
                    ['text' => $answerTrueQuest]
                );
            DB::table('question_details')
                ->where('quest_id', $qid)
                ->where('type', 'dis1')
                ->update(
                    ['text' => $answerFalse1Quest]
                );
            DB::table('question_details')
                ->where('quest_id', $qid)
                ->where('type', 'dis2')
                ->update(
                    ['text' => $answerFalse2Quest]
                );
            DB::table('question_details')
                ->where('quest_id', $qid)
                ->where('type', 'dis3')
                ->update(
                    ['text' => $answerFalse3Quest]
                );
        }

        return $qid;
    }

    public function loadQuestList(Request $request)
    {
        $data = DB::table('questions')
            ->where('test_id', $request->tid)
            ->orderBy('ball', 'asc')
            ->get();
        return $data;
    }

    public function selectedQuest(Request $request)
    {
        $data = DB::table('question_details')
            ->select(DB::raw('id, quest_id, text, type'))
            ->where('quest_id', $request->id)
            ->orderBy('id', 'asc')
            ->get();
        $newData = [];
        $i = 0;
        foreach ($data as $d)
        {
            $newData += [ $i => ['id' => $d->id, 'quest_id' => $d->quest_id, 'text' => htmlspecialchars_decode($d->text), 'type' => $d->type]];
            $i++;
        }
        return $newData;
    }

    public function deleteQuest(Request $request)
    {
        DB::table('questions')->where('id', $request->id)->delete();
    }

    public function testEditors(Request $request)
    {

        $ownerUser = DB::table("test_editors")
                        ->leftjoin('users', 'users.id', 'test_editors.user_id')
                        ->where("test_id", $request->tid)
                        ->where('is_owner', 'T')
                        ->first();
        $owner = $ownerUser->famil.' '.$ownerUser->name.' '.$ownerUser->otch;
        $users = DB::table('users')
                        ->where('id', '<>', $ownerUser->id)
                        ->get();
        $editors = [];
        foreach ($users as $user)
        {
            $userTest = DB::table("test_editors")->where("test_id", $request->tid)->where('user_id', $user->id)->first();
            if ($userTest != null) $editors += [$user->id => $userTest->id];
            else $editors += [$user->id => 0];
        }
        return view('editor.ajax.editorsList', ["owner" => $owner,
                                                "users" => $users,
                                                "editors" => $editors]);
    }

    public function saveTestEditors(Request $request)
    {
        if ($request->testEditors != null)
        {
            foreach ($request->testEditors as $te)
            {
                $userTest = DB::table("test_editors")->where("test_id", $te['tid'])->where('user_id', $te['id'])->first();
                
                if ($userTest != null && $te['status'] == "false")  DB::table("test_editors")->where("id", $userTest->id)->delete(); 
                else if ($userTest == null && $te['status'] == "true")  DB::table("test_editors")->insert(['test_id' => $te['tid'], 'user_id' => $te['id'], 'is_owner' => 'F']); 
            }
            return 0;
        }
        else return -1;
    }

    public function speedFillQuest(Request $request)
    {
        $i = 1;
        for (; $i <= 30; $i++) { 
            $qid = DB::table('questions')->insertGetId(
                ['test_id' => $request->tid, 'ball' => '1']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'вопрос '.$i, 'type' => 'que']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'да', 'type' => 'cor']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis1']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis2']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis3']
            );
        }

        for (; $i <= 50; $i++) { 
            $qid = DB::table('questions')->insertGetId(
                ['test_id' => $request->tid, 'ball' => '2']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'вопрос'.$i, 'type' => 'que']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'да', 'type' => 'cor']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis1']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis2']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis3']
            );
        }
        for (; $i <= 60; $i++) { 
            $qid = DB::table('questions')->insertGetId(
                ['test_id' => $request->tid, 'ball' => '3']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'вопрос'.$i, 'type' => 'que']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'да', 'type' => 'cor']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis1']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis2']
            );
            DB::table('question_details')->insert(
                ['quest_id' => $qid, 'text' => 'нет', 'type' => 'dis3']
            );
        }
    }

    public function duplicate(Request $request)
    {
        $tid = $request->tid;
        if ($tid > 0)
        {
            $testMain   = DB::table('tests')->where('id', $tid)->first();
            $tid_new    = DB::table('tests')->insertGetId(
                            [
                                'user_id'           => $testMain->user_id,
                                'targetAudience_id' => $testMain->targetAudience_id,
                                'type_id'           => $testMain->type_id,
                                'discipline'        => $testMain->discipline.' - копия от '.date('d.m.Y H:i:s', time()),
                                'validator'         => $testMain->validator,
                                'max_ball'          => $testMain->max_ball,
                                'min_ball'          => $testMain->min_ball,
                                'test_time'         => $testMain->test_time,
                                'count_question'    => $testMain->count_question,
                                'status'            => $testMain->status
                            ]
                          );
            $testMain_scatter = DB::table('test_scatter')->where('test_id', $tid)->get();
            foreach ($testMain_scatter as $tms) {
                DB::table('test_scatter')->insert(
                    [
                        'test_id'   => $tid_new,
                        'ball'      => $tms->ball,
                        'ball_count'=> $tms->ball_count
                    ]
                );
            }

            $testMain_editors = DB::table('test_editors')->where('test_id', $tid)->get();
            foreach ($testMain_editors as $tme) {
                DB::table('test_editors')->insert(
                    [
                        'test_id'   => $tid_new,
                        'user_id'   => $tme->user_id,
                        'is_owner'  => $tme->is_owner
                    ]
                );
            }

            $testMain_questions = DB::table('questions')->where('test_id', $tid)->get();
            foreach ($testMain_questions as $tmq) {
                $qid = DB::table('questions')->insertGetId(
                    [
                        'test_id'   => $tid_new,
                        'ball'   => $tmq->ball
                    ]);

                $testMain_questions_details = DB::table('question_details')->where('quest_id', $tmq->id)->get();
                foreach ($testMain_questions_details as $tmqd)
                {
                    DB::table('question_details')->insert([ 'quest_id' => $qid, 'text' => $tmqd->text, 'type' => $tmqd->type ]);
                }
            }
            return 0;
        }
        else return -1;
    }

    public function fulldeletetest(Request $request)
    {
        $tid = $request->tid;
        DB::table('tests')->where('id', $tid)->delete();
        return 0;
    }
}
