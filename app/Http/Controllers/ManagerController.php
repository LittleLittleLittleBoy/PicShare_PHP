<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/6
 * Time: 上午11:43
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController
{
    public function getUser(Request $request){
        $page=$request->get('page');
        $key=$request->get('key');

        if (strlen($key)==0){
            $result=DB::select('select uid,name,province,is_man,phone from "user"  limit 10 offset ?',[($page-1)*10]);
        }else{
            $result=DB::select('select uid,name,province,is_man,phone from "user" WHERE phone LIKE ? limit 10 offset ?',['%'.$key.'%',($page-1)*10]);
        }

        return response()->json($result);
    }

    public function deleteUser(Request $request){
        $uid =$request->get('uid');

        DB::delete('delete from login WHERE login.phone in (SELECT phone from "user" WHERE uid=?)',[$uid]);

        DB::delete('delete from "user" WHERE uid=?',[$uid]);

        DB::delete('delete from relationship WHERE uid=? OR look_uid=?',[$uid,$uid]);

        DB::delete('delete from works WHERE uid=?',[$uid]);

        return response()->json(['success'=>1]);
    }

}