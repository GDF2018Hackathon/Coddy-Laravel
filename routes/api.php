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
  return response(['code' => 418, 'message' => 'Hello, Welcome on Coddy Scanner'], 418)
          ->header('Content-Type', 'application/json')
          ->header('Accept', 'application/json');
});

// Route::post('register', 'Auth\RegisterController@register');
// Route::post('login', 'Auth\LoginController@login');
// Route::get('logout', 'Auth\LoginController@logout');


Route::group(['middleware' => ['web']], function () {
  //github
  Route::get('login/github', 'Auth\LoginController@redirectToProvider');
  Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
  // Route::get('/gitMe', 'GithubController@index');

  //bitbucket
  Route::get('login/bitbucket', 'Auth\LoginController@redirectToProviderbitbucket');
  Route::get('login/bitbucket/callback', 'Auth\LoginController@handleProviderCallbackbitbucket');
  // Route::get('/gitMeBitBucket', 'BitbucketController@index');

  //Route::get('login', 'API\UserController@login');
  Route::get('loginbygithub', 'API\UserController@register');
  Route::get('loginbybitbucket', 'API\UserController@registerbitbucket');
  Route::get('user', 'API\UserController@details');
  Route::get('logout', 'Auth\LoginController@logout');

  // Route::get('repo/all', 'ReposController@index');
  // Route::get('repo/getListRepos/{username?}', 'ReposController@getListRepos');
  // Route::get('repo/getDetailRepo/{name}', 'ReposController@getDetailRepo');

});

Route::group(['prefix' => 'scan', "middleware" => ['web','isuserapi']], function() {
  Route::get('/', function()
  {
      return response(['code' => 400, 'message' => 'Hello, You should have a scan ID'], 400)
              ->header('Content-Type', 'application/json')
              ->header('Accept', 'application/json');
  });
  Route::get('/{id}', 'ScanController@scanAll');
  Route::get('/snif/{id}', 'SnifController@scan');
  Route::get('/metric/{id}', 'MetricController@scan');
});
Route::group(['prefix' => 'repo', "middleware" => ['web','isuserapi']], function() {
  Route::get('all/{source?}', 'ReposController@index');
  // Route::get('getListRepos/{username?}', 'ReposController@getListRepos');
  Route::get('{name}/{source?}', 'ReposController@getDetailRepo');
});


Route::group(['prefix' => 'report'], function() {
	Route::get('/', 'ReportController@index');
	Route::get('/{code}', 'ReportController@getReport')->where('code', '[a-zA-Z0-9]{8,12}');
	Route::get('/mail/{code}', 'ReportController@sendMail')->where(['code' => '[a-zA-Z0-9]{8,12}'])->middleware('auth:api');
});
