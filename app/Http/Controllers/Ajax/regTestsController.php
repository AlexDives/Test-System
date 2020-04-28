<?php

namespace App\Http\Controllers\Ajax;

use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class regTestsController extends Controller
{
    function index()
    {
        return view('registration.testReg');
    }

    function check_login(Request $request)
    {
        if (DB::table('persons')->where('login', $request->log)->first() != null) return -1;
        else return 0;
    }

    function check_email(Request $request)
    {
        if (DB::table('persons')->where('email', $request->email)->first() != null) return -1;
        else return 0;
    }

    function registration(Request $request)
    {
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) return -2;
        // ваш секретный ключ
        $secret = '6LfwCtUUAAAAAJiz-peMncLSTkbHBLAPqyxomlF5';

        $captcha = $request->captcha;
        $verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha), true);
        if ($verify['success']) {
            $login      = $request->login;
            $pass       = $request->pass;
            $email      = $request->email;
            $famil      = $request->famil;
            $name       = $request->name;
            $otch       = $request->otch;
            $birthday   = $request->birthday;
            $gender     = $request->gender;
            $serp       = $request->serp;
            $nump       = $request->nump;
            $fio        = $famil.' '.$name.' '.$otch;
            //$checkEmail = DB::table('persons')->where('email', $email)->first();

            $isEmployed = true;
            $pin = 0;
            while ($isEmployed) {
                $pin =  mt_rand(100000, 999999);
                $c = DB::table('persons')->where('pin', $pin)->first();
                $isEmployed = $c != null;
            }
            $secret_string = '0123456789abcdefghijklmnopqrstuvwxyz';
                // Output: 54esmdr0qf
            $secret_string = substr(str_shuffle($secret_string), 0, 30);
            db::table('persons')->insert(
                [
                    'login'         => $login,
                    'password'      => Hash::make($pass),
                    'email'         => $email,
                    'famil'         => $famil,
                    'name'          => $name,
                    'otch'          => $otch,
                    'birthday'      => $birthday,
                    'gender'        => $gender,
                    'user_hash'     => Hash::make($login.Hash::make($pass)),
                    'PIN'           => $pin,
                    'secret_string' => $secret_string
                ]
            );
            /*
            'pasp_ser'      => $serp,
                    'pasp_num'      => $nump,
            */

            $verificate_link = 'http://test.ltsu.org/verificate?v='.$secret_string.'&email='.$email;
            Mail::send('registration.email', ['link' => $verificate_link, 'fio' => $fio], function ($message) use ($request) {
                $message->from('asu@ltsu.org', 'ЛНУ имени Тараса Шевченко');
                $message->to($request->email, $request->famil.' '.$request->name.' '.$request->otch)->subject('Регистрация аккаунта на test.ltsu.org');
            });
            return 0;
        } else if (!$verify['success']) {
            return 1;
        }
    }
    function verificate(Request $request)
    {
        if (isset($request->v) && isset($request->email))
        {
            $pers = DB::table('persons')->where('secret_string', $request->v)->where('email', $request->email)->first();
            if ($pers != null)
            {
                DB::table('persons')->where('id', $pers->id)->update(['secret_string' => null]);
                session(['user_id' => $pers->id, 'role_id' => 5]);
            }
        }
        echo '<script>location.replace("/");</script>';
    }

    function resetPassword_blade(Request $request)
    {
        return view('reset_pwd');
    }

    function resetPassword(Request $request)
    {
        $pers = DB::table('persons')->where('login', $request->login)->where('email', $request->email)->first();
        if ($pers != null)
        {
            DB::table('persons')->where('id', $pers->id)->update(['password' => Hash::make($request->password)]);
            return 0;
        }
        else return -1;
    }
}
