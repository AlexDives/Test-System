<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class requestController extends Controller
{
    function req(Request $request)
    {
        $user = DB::table('users')->where('id', session('user_id'))->first();

        $famil = $user->famil;
        $name = $user->name;
        $otch = $user->otch;
        $otdel = $user->workplace;
        $doljn = $user->doljn;
        $aud_num = $user->aud_num;
        $tel_num = $user->tel_num;
        $theme = $request->theme;
        $text = $request->text;
        $important = $request->important == 'on' ? 'T' : 'F';

        
        $data = array(
            'famil' => $famil, 
            'name' => $name, 
            'otch' => $otch, 
            'otdel' => $otdel, 
            'doljn' => $doljn, 
            'aud_num' => $aud_num, 
            'tel_num' => $tel_num, 
            'theme' => $theme, 
            'text' => $text,
            'important' => $important
        );
        $client = new Client([
            // You can set any number of default request options.
            'timeout'  => 3.0,
            'headers' => array('Content-Type'  => 'application/json'),
        ]);
        $res = $client->request('POST', 'http://request.ltsu.org/api/SpeedRequest', [
            'body' => json_encode($data)]);

        $body = $res->getBody();
        $returned = $body->getContents();
        return $returned;
    }
}