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
				'pers_tests.timeLeft',
				'pers_tests.minuts_spent',
				'pers_tests.status'
			)
			->where('pers_tests.id', $request->ptid)
			->first();
		if ($test != null) {
			if ($test->status == 2) return back();
			$start_time = $test->start_time != null ? $test->start_time : date("Y-m-d H:i:s", time());
			$timeLeft = $test->timeLeft != 0 ? $test->timeLeft : $test->test_time;
			$minuts_spent = $test->minuts_spent;

			testController::generateTest($request->ptid, $test->id);
			session(['ptid' => $request->ptid]);

			DB::table('pers_tests')
				->where('id', $request->ptid)
				->update(
					[
						'start_time'    => $start_time,
						'timeLeft'      => $timeLeft,
						'minuts_spent'  => $minuts_spent,
						'last_active'   => date("Y-m-d H:i:s", time()),
						'status'        => 1
					]
				);

			$test_name = $test->typeTestName . ' - ' . $test->discipline;
			return view('testing.test', ['test' => $test, 'test_name' => $test_name, 'pers_test_id' => $request->ptid, 'timeLeft' => $timeLeft, 'minuts_spent'  => $minuts_spent, 'role_id' => session('role_id')]);
		} else return back();
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
			->where('pers_test_id', $request->ptid)
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
			->where('id', $request->ptid)
			->first();
		if ($pt != null) {
			$tid = DB::table('pers_test_details')
				->where('pers_test_id', $request->ptid)
				->where('test_id', $request->tid)
				->first();
			if ($tid != null) {
				$qid = DB::table('pers_test_details')
					->where('pers_test_id', $request->ptid)
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
						if ($user != null) 
						{
							$user_nick = $user->login;
							$aud_num   = $user->aud_num;
							$comp_num  = $user->comp_num;
						}
						else 
						{
							$user_nick = 'homework';
							$aud_num   = 'home';
							$comp_num  = '1';
						}
						DB::table('pers_test_details')
							->where('pers_test_id', $request->ptid)
							->where('test_id', $request->tid)
							->where('quest_id', $request->qid)
							->update(
								[
									'answer_id'     => $request->ansid,
									'answer_ball'   => $ansBall,
									'answer_time'   => date("Y-m-d H:i:s", time()),
									'user_nick'     => $user_nick,
									'aud_num'       => $aud_num,
									'comp_num'      => $comp_num
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
			->where('id', $request->ptid)
			->first();

		if ($pt->timeLeft >= $request->leftMinutes) {
			DB::table('pers_tests')
				->where('id', $request->ptid)
				->update(['timeLeft' => $request->leftMinutes, 'minuts_spent' => $request->minuts_spent, 'last_active' => date("Y-m-d H:i:s", time())]);
			return 0;
		} else return -1;
	}

	public function result(Request $request)
	{
		$ptid = $request->ptid;

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
		$pers = DB::table('persons')->leftjoin('pers_tests', 'pers_tests.pers_id', 'persons.id')->where('pers_tests.id', $ptid)->select('persons.*')->first();
		if ($test == null) echo '<script>location.replace("/pers/cabinet");</script>';

		/*$count_answ = DB::table('pers_test_details')->where('pers_test_id', $ptid)->whereNotNull('answer_id')->count();

		if (!$request->has('stop'))
		{
			if (($count_answ < $test->count_question) && (($test->minuts_spent + 1) < $test->test_time))
			{
				DB::table('pers_tests')->where('id', $ptid)->update([ 'status' => 3 ]);
				if ($pers->pers_type == 't') {
					echo '<script>location.replace("/pers/cabinet");</script>'; exit;
				}
				else if($pers->pers_type == 'a')
				{
					echo '<script>location.replace("https://abit.ltsu.org/profile");</script>'; exit;
				}
			}
		} else DB::table('pers_tests')->where('id', $ptid)->update([ 'is_stop' => 'T' ]);*/
		
		
		//////////////////////////////////////////
		$correctBall = DB::table('pers_test_details')
		->where('pers_test_id', $ptid)
		->whereNotNull('answer_id')
		->sum('answer_ball');
		divesController::fix($ptid, $correctBall);
		//////////////////////////////////////////

		$correctBall = DB::table('pers_test_details')
		->where('pers_test_id', $ptid)
		->whereNotNull('answer_id')
		->sum('answer_ball');

		if ($correctBall != 0) {
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
			
			$startTime = $test->start_time;
			$endTime = $test->end_time != null ? $test->end_time : date("Y-m-d H:i:s", time());

			$correntMinutes = $test->minuts_spent;

			$resultTest = $correctBall >= $test->min_ball ? true : false;

			DB::table('pers_tests')
				->where('id', $ptid)
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
			
			//////////////////////////////////////////

			$tmp_pt = DB::table('pers_tests')->where('id', $ptid)->first();
			$tmp_p	= DB::table('persons')->where('id', $tmp_pt->pers_id)->first();
			if ($tmp_p->pers_type == 'a')
			{
				$tmp_pred = DB::table('abit_predmets')->where('test_id', $tmp_pt->test_id)->first();
				if ($tmp_pred != null)
				{
					$tmp_examGroup = DB::table('abit_examenGroup')->where('predmet_id', $tmp_pred->id)->get();
					$tmp_statementPers = DB::table('abit_statements')->where('person_id', $tmp_p->id)->whereNull('date_return')->get();
					foreach ($tmp_examGroup as $teg) {
						foreach ($tmp_statementPers as $tsp) {
							DB::table('abit_examCard')->where('state_id', $tsp->id)->where('exam_id', $teg->id)->update([
								'ball'	=> $correctBall
							]);
							DB::table('abit_examCard')->whereNull('date_exam')->where('state_id', $tsp->id)->where('exam_id', $teg->id)->update([
								'date_exam'	=> $tmp_pt->start_time
							]);
						}
					}
				}
			}

			//////////////////////////////////////////
			//session('ptid', '');
			$request->session()->forget('ptid');
			$pers_type = DB::table('persons')->leftjoin('pers_tests', 'pers_tests.pers_id', 'persons.id')->where('pers_tests.id', $ptid)->first()->pers_type;
			//$request->session()->getHandler()->destroy($request->session()->getID());
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
					'ptid'  => $ptid,
					'pers_type' => $pers_type,
					'pid'	=> $tmp_p->id
				]
			);
		}
		//return redirect()->away("https://abit.ltsu.org/profile");
		return redirect()->away("/");
	}

	function speedTest(Request $request)
	{
		if ((session('role_id') == 1)) {
			$pt = DB::table('pers_tests')
				->where('id', $request->ptid)
				->first();
				
			if ($pt != null) {
				$pers_test_details = DB::table('pers_test_details')
					->where('pers_test_id', $request->ptid)
					->where(function ($query) {
						$query->whereNull('answer_ball')
							  ->orWhere('answer_ball', 0);
					})
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
							if ($user != null) 
							{
								$user_nick = $user->login;
								$aud_num   = $user->aud_num;
								$comp_num  = $user->comp_num;
							}
							else 
							{
								$user_nick = 'homework';
								$aud_num   = 'home';
								$comp_num  = '1';
							}
							DB::table('pers_test_details')
								->where('id', $ptd->id)
								->update(
									[
										'answer_id'     => $ansDetail->id,
										'answer_ball'   => $ansBall,
										'answer_time'   => date("Y-m-d H:i:s", time()),
										'user_nick'     => $user_nick,
										'aud_num'       => $aud_num,
										'comp_num'      => $comp_num
									]
							);
							
						}
					}
					return 0; // all right
				} else return -3; // Не найден qid, обратитесь к администратору! 
			} else return -2; // Не найден tid, обратитесь к администратору! 
		}
	}
}
