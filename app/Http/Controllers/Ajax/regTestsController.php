<?php

namespace App\Http\Controllers\Ajax;

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
        // ваш секретный ключ
        $secret = '6LfwCtUUAAAAAJiz-peMncLSTkbHBLAPqyxomlF5';

        $captcha = $request->captcha;
        $verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha), true);
        if ($verify['success']) {
            $login = $request->login;
            $pass = $request->pass;
            $email = $request->email;
            $famil = $request->famil;
            $name = $request->name;
            $otch = $request->otch;
            $birthday = $request->birthday;
            $gender = $request->gender;
            $serp = $request->serp;
            $nump = $request->nump;

            //$checkEmail = DB::table('persons')->where('email', $email)->first();

            
            

            $isEmployed = true;
            $pin = 0;
            while ($isEmployed) {
                $pin =  mt_rand(100000, 999999);
                $c = DB::table('persons')->where('pin', $pin)->first();

                $isEmployed = $c != null;
            }
            db::table('Persons')->insert(
                [
                    'login' => $login,
                    'password' => Hash::make($pass),
                    'email' => $email,
                    'famil' => $famil,
                    'name' => $name,
                    'otch' => $otch,
                    'birthday' => $birthday,
                    'gender' => $gender,
                    'pasp_ser' => $serp,
                    'pasp_num' => $nump,
                    'PIN' => $pin
                ]
            );
            return 0;
            /*$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                // Output: 54esmdr0qf
                echo substr(str_shuffle($permitted_chars), 0, 10);*/
        } else if (!$verify['success']) {
            return 1;
        }
    }
}
