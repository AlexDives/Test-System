<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DateTime;

class managerController extends Controller
{
    public function main(Request $request)
    {
        $roles = managerController::loadRoles();
        $users = managerController::loadUsers();
        $userStatus = [];
        foreach ($users as $user) {
            $timestampStart = strtotime($user->last_active);
            $timestampEnd = time();
            $seconds = ($timestampEnd - $timestampStart);
            $userStatus += [$user->id => $seconds >= 120 ? 'offline' : 'online'];
        }
        return view('users.userManager', [
            'roles' => $roles,
            'users' => $users,
            'userStatus' => $userStatus
        ]);
    }

    public function loadUserTable(Request $request)
    {
        $users = managerController::loadUsers();
        $userStatus = [];
        foreach ($users as $user) {
            $timestampStart = strtotime($user->last_active);
            $timestampEnd = time();
            $seconds = ($timestampEnd - $timestampStart);
            $userStatus += [$user->id => $seconds >= 120 ? 'offline' : 'online'];
        }
        return view('users.ajaxUsersTable', [
            'users' => $users,
            'userStatus' => $userStatus
        ]);
    }

    public function loadRoles()
    {
        return DB::table('roles')->get();
    }

    public function loadUsers()
    {
        return DB::table('users')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('users.*', 'roles.id as role_id')->orderby("users.last_active", "desc")->get();
    }

    public function saveUser(Request $request)
    {
         if ($request->uid <= 0) {
            $id = DB::table('users')->insertGetId(
                [
                    'login' => $request->login, 'password' => Hash::make($request->password),
                    'famil' => $request->famil, 'name' => $request->name, 'otch' => $request->otch,
                    'workplace' => $request->workplace, 'doljn' => $request->doljn, 'aud_num' => $request->aud_num,
                    'tel_num' => $request->tel_num, 'is_block' => $request->is_block == 'on' ? 'F' : 'T',
                    'secret_question' => $request->secret_question, 'secret_answer' => $request->secret_answer
                ]
            );
            DB::table('user_roles')->insert(['user_id' => $id, 'role_id' => $request->roles]);
            return $id;
        } else if ($request->uid > 0) {
            if ($request->password == null && $request->password_two == null) {
                DB::table('users')->where('id', $request->uid)->update(
                    [
                        'login' => $request->login, 'famil' => $request->famil, 'name' => $request->name,
                        'otch' => $request->otch, 'workplace' => $request->workplace, 'doljn' => $request->doljn,
                        'aud_num' => $request->aud_num, 'tel_num' => $request->tel_num, 'is_block' => $request->is_block == 'on' ? 'F' : 'T',
                        'secret_question' => $request->secret_question, 'secret_answer' => $request->secret_answer
                    ]
                );
            } else if (
                trim($request->password) != '' && trim($request->password_two) != '' &&
                $request->password == $request->password_two
            ) {
                DB::table('users')->where('id', $request->uid)->update(
                    [
                        'login' => $request->login, 'password' => Hash::make($request->password),
                        'famil' => $request->famil, 'name' => $request->name, 'otch' => $request->otch,
                        'workplace' => $request->workplace, 'doljn' => $request->doljn, 'aud_num' => $request->aud_num,
                        'tel_num' => $request->tel_num, 'is_block' => $request->is_block == 'on' ? 'F' : 'T',
                        'secret_question' => $request->secret_question, 'secret_answer' => $request->secret_answer
                    ]
                );
            }
            DB::table('user_roles')->where('user_id', $request->uid)->update(['role_id' => $request->roles]);
            return $request->uid;
        }
        return -1;
    }
    public function deleteUser(Request $request)
    {
        DB::table('users')->where('id', $request->uid)->delete();
        return 1;
    }
}
