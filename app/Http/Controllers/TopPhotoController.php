<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/6
 * Time: 上午11:16
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class TopPhotoController extends Controller
{
    public function getTopPhoto(){
        $result=DB::select('select works.pid,count(*),works.url photo_url from works,public_like WHERE works.pid=public_like.pid  GROUP BY works.pid,works.url ORDER by COUNT(*)limit 10');
        return response()->json($result);
    }
}