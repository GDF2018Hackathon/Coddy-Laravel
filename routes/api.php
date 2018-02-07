<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function()
{
  return response()->json('Hello, Welcome on Coddy Scanner');
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['web']], function () {
  Route::get('login/github', 'Auth\LoginController@redirectToProvider');
  Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
  Route::get('/gitMe', 'GithubController@index');
});

// Route::group(['prefix' => 'scan', 'middleware' => 'auth:api'], function() {
Route::group(['prefix' => 'scan'], function() {
  // Route::get('/', 'ReposController@index');
  // Route::get('/getListRepos/{username?}', 'ReposController@getListRepos');
  // Route::get('/getDetailRepo/{name}', 'ReposController@getDetailRepo');
  Route::get('/', function()
  {
    return response(['code' => 400, 'message' => 'Hello, You should have a scan ID'], 400)
            ->header('Content-Type', 'application/json')
            ->header('Accept', 'application/json');
  });
  Route::get('/test/{repoName}', 'ReportController@testScan');
  Route::get('/metric/{id}/{path?}', 'MetricController@scan');
  Route::get('/{user}/{name}/{path?}', 'ScanController@scanAll');
  // Route::get('/snif/{id}/', 'SnifController@scan');
});

Route::group(['prefix' => 'report'], function() {
	Route::get('/', 'ReportController@index');
	Route::get('/{code}', 'ReportController@getReport')->where('code', '[a-zA-Z0-9\-]{10,30}');
	Route::get('/mail/{code}', 'ReportController@sendMail')->where(['code' => '[a-zA-Z0-9]{10,30}']);
});

Route::apiResource('report', 'ReportController', [
	'only' => ['show'],
	'parameters' => ['report' => 'code']
]);

Route::apiResource('report', 'ReportController', [
	'only' => ['index'],
]);

Route::apiResource('donnation', 'DonnationController');
