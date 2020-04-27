<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class questionController extends Controller
{

    public function insert(Request $request)
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
        }
    }
    public function testList()
    {
        return DB::table('tests')
            ->leftJoin('target_audience', 'target_audience.id', '=', 'tests.targetAudience_id')
            ->leftJoin('type_test', 'type_test.id', '=', 'tests.type_id')
            ->leftJoin('test_editors', 'test_editors.test_id', '=', 'tests.id')
            ->select('tests.*', 'target_audience.name as targetAudienceName', 'type_test.name as typeTestName')
            ->get();
    }
}