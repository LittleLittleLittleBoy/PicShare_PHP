<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/11
 * Time: 下午4:54
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareWorkController extends Controller
{
    public function addShareWork(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $pid=$request->get('pid');

        DB::insert('insert into user_public(uid, pid, "time") VALUES (?,?,?)',[$uid,$pid,time()]);
        return response()->json(['successMessage'=>1]);
    }

    public function getShareWork(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('uid');


        $result=DB::select('select works.pid,works.url photo_url,"user".name username,user_public.type,works.description,datetime(user_public.time,\'unixepoch\') createtime,"user".photo_url user_url from user_public,works,"user" WHERE user_public.pid=works.pid AND works.uid=? AND user_public.uid="user".uid order by user_public."time" DESC ',[$uid]);

        return response()->json($result);
    }

    public function getLikes(Request $request){
        $pid=$request->get('pid');

        $count=DB::select('select *  from public_like WHERE pid=?',[$pid]);
        $likes=DB::select('select DISTINCT "user".photo_url,"user".uid from public_like,"user" WHERE public_like.uid="user".uid AND pid=?',[$pid]);

        $result=[
            'countNumber'=>count($count),
            'likes'=>$likes
        ];

        return response()->json($result);
    }

    public function addLike(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $pid=$request->get('pid');

        DB::insert('insert into public_like(pid, uid) VALUES (?,?)',[$pid,$uid]);

        return response()->json(['successMessage'=>1]);

    }

    public function sharePic(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $pid=$request->get('pid');

        DB::insert('insert into user_public(uid, pid, type, "time") VALUES (?,?,?,?)',[$uid,$pid,1,time()]);

        return response()->json(['successMessage'=>1]);

    }

    public function getComment(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $pid=$request->get('pid');

        $result=DB::select('select cid,comments.uid,"user".name username,content from comments,"user" WHERE comments.uid="user".uid AND pid=? ORDER BY "user".create_time DESC ',[$pid]);

        return response()->json($result);
    }

    public function addComment(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $pid=$request->get('pid');
        $content=$request->get('comment');

        DB::insert('insert into comments(cid,pid, uid, content, create_time) VALUES (?,?,?,?,?)',[md5($pid.$uid.time()),$pid,$uid,$content,time()]);
        return response()->json(['successMessage'=>1]);
    }

    public function getFriendPicture(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $result=DB::select('select works.pid,works.url photo_url,"user".name username,user_public.type,works.description,datetime(user_public.time,\'unixepoch\') createtime,"user".photo_url user_url from user_public,works,"user" WHERE user_public.pid=works.pid AND user_public.uid="user".uid AND works.uid=? OR works.uid IN(select look_uid from relationship WHERE uid=?) order by user_public."time" DESC ',[$uid,$uid]);

        return response()->json($result);

    }
}