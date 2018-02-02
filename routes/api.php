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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['web']], function () {
  Route::get('login/github', 'Auth\LoginController@redirectToProvider');
  Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
  Route::get('/gitMe', 'GithubController@index');
});

Route::group(['prefix' => 'scan', 'middleware' => 'auth:api'], function() {
  Route::get('/', 'ReposController@index');
  Route::get('/getListRepos/{username?}', 'ReposController@getListRepos');
  Route::get('/getDetailRepo/{name}', 'ReposController@getDetailRepo');
});

Route::group(['prefix' => 'report'], function() {
	Route::get('/', 'ReportController@index');
	Route::get('/{code}', 'ReportController@getReport')->where('code', '[a-zA-Z0-9]{8,12}');
	Route::get('/mail/{code}', 'ReportController@sendMail')->where(['code' => '[a-zA-Z0-9]{8,12}']);
});
