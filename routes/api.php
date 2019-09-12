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

Route::get('/', function()
{
  return response(['code' => 418, 'message' => 'Hello, Welcome on Coddy Scanner'], 418)
          ->header('Content-Type', 'application/json')
          ->header('Accept', 'application/json');
});

Route::group(['middleware' => ['web']], function () {
  //github
  Route::get('login/github', 'Auth\LoginController@redirectToProvider');
  Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
  Route::get('loginbygithub', 'API\UserController@register');

  //bitbucket
  Route::get('login/bitbucket', 'Auth\LoginController@redirectToProviderbitbucket');
  Route::get('login/bitbucket/callback', 'Auth\LoginController@handleProviderCallbackbitbucket');
  Route::get('loginbybitbucket', 'API\UserController@registerbitbucket');

  Route::get('user', 'API\UserController@details');
  Route::get('logout', 'Auth\LoginController@logout');
});


Route::group(['prefix' => 'scan', "middleware" => ['web','isuserapi']], function() {
  Route::get('/', function()
  {
    return redirect()->route('Error400Bad');
  });
  Route::get('/metric/{id}/{path?}', 'MetricController@scan');
  Route::get('/{reponame}/{branch?}/{path?}/{user?}', 'ReportController@scanAll');
});

Route::group(['prefix' => 'repo', "middleware" => ['web','isuserapi']], function() {
  Route::get('/', function()
  {
    return redirect()->route('Error400Bad');

  });
  Route::get('all', 'ReposController@index');
  Route::get('{name}', 'ReposController@getDetailRepo');
});


Route::group(['prefix' => 'report'], function() {
  Route::get('/', 'ReportController@index');
  Route::get('/mail/{code}', 'ReportController@sendMail')->where('code', '[a-zA-Z0-9\-]{8,30}')->middleware(['web', 'isuserapi'])->name('SendMailRoute');
	Route::get('/{code}', 'ReportController@getReport')->where('code', '[a-zA-Z0-9\-]{8,30}');
});

Route::apiResource('report', 'ReportController', [
	'only' => ['show'],
	'parameters' => ['report' => 'code']
]);

Route::apiResource('report', 'ReportController', [
	'only' => ['index'],
]);

Route::apiResource('donation', 'DonnationController');

Route::group(['prefix' => 'faq'], function() {
	Route::get('/', 'FAQController@index');
  Route::get('/{id}', 'FAQController@show');
	Route::get('/category/{id}', 'FAQController@section');
});

Route::group(['prefix' => 'category'], function() {
	Route::get('/', 'CategoryController@index');
  Route::get('/{id}', 'CategoryController@show');
});

Route::get('/error400', function(Request $request)
{
    return response(['code' => 400, 'message' => 'Hello, You should have an ID'], 400)
            ->header('Content-Type', 'application/json')
            ->header('Accept', 'application/json');
})->name('Error400Bad');

Route::get('/error404', function(Request $request)
{
    return response(['code' => 404, 'message' => 'Page not found'], 404)
            ->header('Content-Type', 'application/json')
            ->header('Accept', 'application/json');
})->name('Error404Bad');
