<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/9
 * Time: 下午3:24
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumPhotoController extends Controller
{
    public function addNewPhoto(Request $request){
        $uid='1e9e813173de461e046af00fa0d9b022';
        $request_body = file_get_contents('php://input');

        return response()->json(['name'=>$request_body]);
    }

    public function deletePhoto(Request $request){

        $pid=$request->get('pid');

        DB::delete('delete from works WHERE pid=?',[$pid]);
        DB::delete('delete from work_album WHERE pid=?',[$pid]);
        DB::delete('delete from user_public WHERE pid=?',[$pid]);
        DB::delete('delete from public_like WHERE pid=?',[$pid]);
        DB::delete('delete from comments WHERE pid=?',[$pid]);
        DB::delete('delete from activity_work WHERE pid=?',[$pid]);

        return response()->json(['successMessage'=>0]);
    }

    public function changeHeadPhoto(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $pid=$request->get('pid');

        DB::update('update user set photo_url=(SELECT url from works WHERE works.pid=?) WHERE uid=?',[$pid,$uid]);

        return response(['successMessage'=>0]);
    }

    public function getAllPhoto(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $aid=$request->get('aid');

        $result=DB::select('select works.pid,works.description,works.rate,works.tags,works.url src from work_album,works WHERE work_album.aid=? AND work_album.pid=works.pid AND works.uid=?',[$aid,$uid]);

        return response()->json($result);
    }


    public function sharePhoto(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $pid=$request->get('pid');

        DB::insert('insert into user_public(uid, pid, "time") VALUES (?,?,?)',[$uid,$pid,time()]);

        return response()->json(['successMessage'=>0]);
    }

}