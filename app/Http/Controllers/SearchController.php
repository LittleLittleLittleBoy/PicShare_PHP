<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/12
 * Time: 上午1:50
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request){

        $key=$request->get('key');

        if ($key==null||strlen($key)==0){
            return response()->json("");
        }

        $user=DB::select('select uid,name username,description,photo_url userImage,is_man isman,watched,watch from "user" WHERE "user".name LIKE ?',['%'.$key.'%']);
        $work=DB::select('select DISTINCT works.pid,works.url photo_url,"user".name username,user_public.type,works.description,datetime(user_public.time,\'unixepoch\') createtime,"user".photo_url user_url from user_public,works,"user" WHERE user_public.pid=works.pid AND user_public.uid="user".uid AND works.tags LIKE ?',['%'.$key.'%']);

        $result=[
            'user'=>$user,
            'work'=>$work
        ];

        return response()->json($result);

    }
}