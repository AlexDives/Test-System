<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class authController extends Controller
{
    public function auth(Request $request){
        $login = trim($request->login);
        $pass = trim($request->pwd);

        $login = htmlspecialchars($login);
        $login = stripslashes($login);
        $pass = htmlspecialchars($pass);
        $pass = stripslashes($pass);
        
        //DB::table('users')->where('id', 1)->update(['login' => $login, 'password' => Hash::make($pass)]);
        
        $user = DB::table('users')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('users.*', 'roles.id as role_id')
            ->where(['login' => $login])->first();
        
        if ($user != null) {
            if (Hash::check($pass, $user->password)) {
                if ($user->is_block == 'F') {
                    session(['user_id' => $user->id, 'role_id' => $user->role_id]);
                    $data = ['role_id' => $user->role_id];
                    return $data;
                }
                else return -1;
            }
            else return -2;
        }
        else 
        {
            $persons = DB::table('persons')->where(['login' => $login])->first();
            if ($persons != null)
            {
                if ($persons->secret_strin != null) {
                    if (Hash::check($pass, $persons->password)) {
                        if ($persons->is_block == 'F') {
                            session(['user_id' => $persons->id, 'role_id' => 5]);
                            $data = ['role_id' => 5];
                            return $data;
                        }
                        else return -1;
                    }
                    else return -2;
                }
                else return -4;
            }
            else return -3;
        }
        
    }
    public function quit(Request $request)
    {
        $request->session()->getHandler()->destroy($request->session()->getID());
        echo '<script>location.replace("/");</script>'; exit;
    }
    /*public static function checkRole()
    {
        $role = DB::table('roles')
            ->leftJoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
            ->select('roles.*')
            ->where('user_roles.user_id', session('user_id') )->first();
            session(['role_id' => $role->id]);   
    }
    public static function checkAuth(Request $request)
    {
        if (!$request->session()->has('user_id')) {
            echo '<script>location.replace("/");</script>'; exit;
            exit();  
        } else {
            $user = DB::table('users')->where(['id' => session('user_id')])->first();
            if ($user->is_block == 'T') 
            {
                $request->session()->getHandler()->destroy($request->session()->getID());
                echo '<script>location.replace("/");</script>'; exit;
            }
            else 
            {
                DB::table('users')->where('id', session('user_id'))->update(['last_active' => date("Y-m-d H:i:s",time())]);
            }
        }
    }*/
}
