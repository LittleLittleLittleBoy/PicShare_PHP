<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/4
 * Time: 下午7:20
 */

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;


class LoginController extends Controller
{
    public function login(Request $request){
        $phone=$request->get('username');
        $password=$request->post('password');

        $user=DB::select('select * from login WHERE phone=? AND pass=?',[$phone,md5($password)]);
        if (count($user)==1){
            $uid=DB::select('select uid from "user" WHERE phone=?',[$phone]);
            return response()->json(['successMessage'=>0,'uid'=>$uid[0]->uid]);
        }else{
            return response()->json(['successMessage'=>1]);
        }

    }

    public function register(Request $request){
        $phone=$request->post('phone');
        $password=$request->post('password');
        $username=$request->post('username');
        $description=$request->post('description');
        $sex=$request->post('sex');
        $place=$request->post('place');


        $user=DB::select('select * from login WHERE phone=?',[$phone]);
        if (count($user)==0){
            DB::insert('insert into login (phone, pass) values (?, ?)', [$phone, md5($password)]);
            DB::insert('insert into "user" (uid, name,photo_url,description,province,is_man,create_time,phone) values (?, ?,?,?,?,?,?,?)',
                [md5($phone.time()),$username,'http://127.0.0.1:8888/photo/picture.jpeg',$description,$place,$sex,time(),$phone]);
            return response()->json(['errormessage'=>0]);
        }else{
            return response()->json(['errormessage'=>1]);
        }
    }

    public function update(Request $request){


        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');


        $name=$request->post('name');
        $description=$request->post('description');
        $province=$request->post('place');
        $isman=$request->post('sex');

        $pass=$request->post('password');

        DB::update('update user set name=?,description=?,province=?,is_man=?WHERE uid=?',[$name,$description,$province,$isman,$uid]);


        DB::update('update login set pass=? WHERE uid=?',[md5($pass),$uid]);

        return response()->json(['errormessage'=>0]);
    }


}