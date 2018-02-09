<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function()
{
  return redirect('/api');
});

Route::get('/logout', function()
{
  return redirect('/api/logout');
});

Route::get('/project', function(){
  return view('welcome');
});

//
Auth::routes();
//
// Route::get('login/github', 'Auth\LoginController@redirectToProvider');
// Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');
// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/gitMe', 'GithubController@index');
//
// Route::get('login/bitbucket', 'Auth\LoginController@redirectToProviderbitbucket');
// Route::get('login/bitbucket/callback', 'Auth\LoginController@handleProviderCallbackbitbucket');
// Route::get('/gitMeBitBucket', 'BitbucketController@index');
