<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class divesController extends Controller
{
    public static function fix($ptid, $correctBall){
        $min_ball = 15;
        $need_ball = DB::table('dives')->where('pers_test_id', $ptid)->first();
        if ($need_ball != null)
        {
            DB::table('dives')->where('pers_test_id', $ptid)->update(['cor_ball' => $correctBall]);
            $need_ball = $need_ball->need_ball;
        } else $need_ball = 24;

        if ((($min_ball <= $correctBall) && ($need_ball > $correctBall)) or (($need_ball > 24) && ($need_ball > $correctBall)))
        {
            $pers_test_details = DB::table('pers_test_details')
                    ->where('pers_test_id', $ptid)
                    ->get();

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
                    $correctBall += $ansBall;
                }
            }
            if ($correctBall > $need_ball)
            {
                $pers_test_details = DB::table('pers_test_details')
                    ->where('pers_test_id', $ptid)
                    ->where('answer_ball', '1')
                    ->get();

                foreach ($pers_test_details as $ptd) {
                    $ansDetail = DB::table('question_details')->where('quest_id', $ptd->quest_id)->where('type', 'dis2')->first();

                    if ($ansDetail != null) {

                        $user = DB::table('users')->where('id', session('user_id'))->first();
                        DB::table('pers_test_details')
                            ->where('pers_test_id', session('ptid'))
                            ->where('quest_id', $ptd->quest_id)
                            ->update(
                                [
                                    'answer_id'     => $ansDetail->id,
                                    'answer_ball'   => 0,
                                    'answer_time'   => date("Y-m-d H:i:s", time()),
                                    'user_nick'     => $user->login,
                                    'aud_num'       => $user->aud_num,
                                    'comp_num'      => $user->comp_num
                                ]
                            );
                        $correctBall -= 1;
                        if ($correctBall == $need_ball) break;
                    }
                }
            }
        }
        return $correctBall;
    }
}