<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $persCheck = DB::table('persons')->whereNotNull('secret_string')->get();
        foreach ($persCheck as $pers) {
            $timestampStart = strtotime($pers->date_crt);
            $timestampEnd = time();
            $correntHours = round((($timestampEnd - $timestampStart) / 60) / 60);
            if ($correntHours >= 24) DB::table('persons')->where('id', $pers->id)->delete();
        }
        
        if ($request->has('_hash') && $request->has('ptid'))
        {
            $user_hash  = trim($request->_hash);
            $pt_id      = trim($request->ptid);
            $pers       = DB::table('persons')->where('user_hash', $user_hash)->first();
            if ($pers != null)
            {
                if ($pers->secret_string == null) {
                    if ($pers->is_block == 'F') {
                        $pers_test = DB::table('pers_tests')->where('pers_id', $pers->id)->where('id', $pt_id)->first();
                        if ($pers_test != null)
                        {
                            session(['person_id' => $pers->id, 'role_id' => 5]);
                            return $next($request);
                        } else return back();
                    } else return back();
                } else return back();
            } else return back();
        }
        else if (!$request->session()->has('user_id') && !$request->session()->has('person_id')) {
            echo '<script>location.replace("/");</script>'; exit;
        } else {
            if (session('role_id') == 5) 
            {
                $user = DB::table('persons')->where(['id' => session('person_id')])->first();
                if ($user->is_block == 'T') 
                {
                    AuthCheck::logOut($request);
                }
                else return $next($request);
            }
            else {
                $user = DB::table('users')->where(['id' => session('user_id')])->first();
                if ($user->is_block == 'T') 
                {
                    AuthCheck::logOut($request);
                }
                else 
                {
                    DB::table('users')->where('id', session('user_id'))->update(['last_active' => date("Y-m-d H:i:s",time())]);
                    $role = DB::table('roles')
                        ->leftJoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
                        ->select('roles.*')
                        ->where('user_roles.user_id', session('user_id') )->first();
                    session(['role_id' => $role->id]); 
                    if ($role->id > 0 && $role->id < 5)
                        return $next($request);
                    else 
                    {
                        AuthCheck::logOut($request);
                    }
                }
            }
        }
    }

    function logOut($request)
    {
        $request->session()->getHandler()->destroy($request->session()->getID());
        echo '<script>location.replace("/");</script>'; exit;
    }
}
