<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

use Illuminate\Support\Facades\DB;

$router->post('/login',['uses'=>'LoginController@login']);
$router->post('/register','LoginController@register');
$router->post('/update','LoginController@update');

$router->get('/manager/getUser','ManagerController@getUser');
$router->get('/manager/deleteUser','ManagerController@deleteUser');

$router->get('/deleteAlbum','AlbumController@deleteAlbums');
$router->get('/addAlbum','AlbumController@addNewAlbums');
$router->get('/getAlbums','AlbumController@getAlbums');
$router->get('changeAlbums','AlbumController@changeAlbums');

$router->post('/addNewPhoto',function (\Illuminate\Http\Request $request){

    //$uid='1e9e813173de461e046af00fa0d9b022';
    $uid=$request->post('currentUid');


    $file = $request->file('pic');
    $aid=$request->post('aid');
    $description=$request->post('description');
    $tags=$request->post('tags');

    $fileName=md5($uid.$aid.$file->getClientOriginalName().time());
    $fileUrl='http://127.0.0.1:8888/photo/'.$fileName;
    $file->move('/Applications/MAMP/htdocs/photo', $fileName);

    $arr=getimagesize('/Applications/MAMP/htdocs/photo/'.$fileName);
    $rate=$arr[1]/$arr[0];

    $pid=md5($uid.$fileName);

    DB::insert('insert into works(pid,type,uid,url,description,create_time,tags,rate) VALUES (?,?,?,?,?,?,?,?)',[$pid,0,$uid,$fileUrl,$description,time(),$tags,$rate]);
    DB::insert('insert into work_album(aid,pid) VALUES (?,?)',[$aid,$pid]);

    return response()->json(['successMessage'=>1]);
});
$router->get('/deletePhoto','AlbumPhotoController@deletePhoto');
$router->get('/changeHeadPhoto','AlbumPhotoController@changeHeadPhoto');
$router->get('/getAllPhoto','AlbumPhotoController@getAllPhoto');
$router->get('/sharePhoto','AlbumPhotoController@sharePhoto');

$router->get('/getMyInformation','UserInformationController@getMyInformation');


$router->get('/addShareWork','ShareWorkController@addShareWork');
$router->get('/getShareWork','ShareWorkController@getShareWork');
$router->get('/addLike','ShareWorkController@addLike');
$router->get('/getLikes','ShareWorkController@getLikes');
$router->get('/sharePic','ShareWorkController@sharePic');
$router->get('/addComment','ShareWorkController@addComment');
$router->get('/getComment','ShareWorkController@getComment');
$router->get('/getFriendPicture','ShareWorkController@getFriendPicture');


$router->get('/getTopPhoto','TopPhotoController@getTopPhoto');

$router->get('/getOtherUserInformation','UserInformationController@getOtherUserInformation');

$router->get('/isFriend','FriendController@isFriend');
$router->get('/beFriend','FriendController@beFriend');
$router->get('/getFriends','FriendController@getFriends');
$router->get('/beNotFriend','FriendController@beNotFriend');

$router->get('/search','SearchController@search');