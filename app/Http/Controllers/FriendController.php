<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/6
 * Time: 上午9:48
 */

namespace App\Http\Controllers;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function getFriends(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $result=DB::select('select relationship."group","user".uid,"user".name username,"user".description,"user".is_man sex,"user".watch,"user".watched,"user".photo_url from relationship,"user" WHERE relationship.uid=? AND relationship.look_uid="user".uid',[$uid]);
        return response()->json($result);
    }

    public function isFriend(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $friendUid=$request->get('uid');

        $result=DB::select('select * from relationship WHERE relationship.uid=? AND relationship.look_uid=?',[$uid,$friendUid]);

        if (count($result)==0){
            return response()->json(['isFriend'=>0]);
        }else{
            return response()->json(['isFriend'=>1]);
        }
    }

    public function beFriend(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $lookUid=$request->get('uid');
        $group=$request->get('group');

        DB::insert('insert into relationship(uid, look_uid,"group") VALUES (?,?,?)',[$uid,$lookUid,$group]);
        DB::update('update "user" set watch=watch+1 WHERE uid=?',[$uid]);
        DB::update('update "user" set watched=watched+1 WHERE uid=?',[$lookUid]);

        return response()->json(['success'=>1]);
    }

    public function beNotFriend(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $lookUid=$request->get('uid');


        DB::insert('delete from relationship WHERE uid=? AND look_uid=?',[$uid,$lookUid]);
        DB::update('update "user" set watch=watch-1 WHERE uid=?',[$uid]);
        DB::update('update "user" set watched=watched-1 WHERE uid=?',[$lookUid]);

        return response()->json(['success'=>1]);
    }

}