<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/11
 * Time: 下午2:50
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserInformationController extends Controller
{
    public function getMyInformation(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $result=DB::select('select uid,name username,description,photo_url,is_man sex,date(create_time, \'unixepoch\') jointime,phone phoneNumber,watch,watched,province place from user WHERE uid=?',[$uid]);

        return response()->json($result);
    }

    public function getOtherUserInformation(Request $request){
        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('uid');

        $result=DB::select('select uid,name username,description,photo_url userImage,is_man isman,watched,watch from "user" WHERE uid=? ',[$uid]);

        return response()->json($result);
    }
}