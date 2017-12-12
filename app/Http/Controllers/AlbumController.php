<?php
/**
 * Created by PhpStorm.
 * User: liweimin
 * Date: 2017/12/6
 * Time: 下午9:00
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    //TODO add photo_url
    public function getAlbums(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');
        $result=DB::select('select album.aid,title,tag albumTag,b.albumNumber,c.url albumImage from album LEFT JOIN (SELECT aid,COUNT(*) albumNumber FROM work_album GROUP BY aid) b on album.aid=b.aid LEFT JOIN (select aid,works.url url from work_album,works WHERE work_album.pid=works.pid limit 0,1 ) c on album.aid=c.aid WHERE album.uid=? ',[$uid,]);
        return response()->json($result);

    }

    public function addNewAlbums(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $title=$request->get('albumName');
        $tag=$request->get('tags');
        DB::insert('insert into album(aid,uid,title,tag,create_time) VALUES (?,?,?,?,?)',[md5($title.$uid.$uid.time()),$uid,$title,$tag,time()]);

        return response()->json(['successMessage'=>1]);
    }

    public function deleteAlbums(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $aid=$request->get('aid');
        DB::delete('delete from album WHERE aid=? AND uid=?',[$aid,$uid]);

        return response()->json(['successMessage'=>1]);
    }

    public function changeAlbums(Request $request){

        //$uid='1e9e813173de461e046af00fa0d9b022';
        $uid=$request->get('currentUid');

        $aid=$request->get('aid');
        $title=$request->get('title');
        $tag=$request->get('tag');

        DB::update('update album set title=?,tag=? WHERE aid=? AND uid=?',[$title,$tag,$aid,$uid]);

        return response()->json(['successMessage'=>1]);

    }
}