<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\API\UserController as UserController;
use App\Http\Controllers\ApiGithubController as APIGITHUB;
use App\Http\Controllers\ApiBitbucketController as APIBITBUCKET;

class ReposController extends  Controller
{
	public $username;

	public function index()
	{
		if(Auth::check()){
				$user = Auth::user();
				$this->user = $user;
				$this->username = $user->nickname;
				if( preg_match('/^[0-9]+$/', $this->user->social_id)	){
					return response()->json(APIGITHUB::getRepos($this->username));
				}else{
					return response()->json(APIBITBUCKET::getRepos($this->username));
				}
		}
		else{
				return response()->json(["code" => 401, 'message' => 'Unauthorised'], 401);
		}
	}

	public function getListRepos()
	{
		// URL : https://api.github.com/users/{USERNAME}/repos
		return response()->json(APIGITHUB::getRepos($this->username));
	}

	public function getDetailRepo($name)
	{
		// URL : https://api.github.com/repos/{USERNAME}/{NAME}
		//return response()->json([Auth::user(), $this->username]);

		$keys = [
			'id',
			'name',
			'html_url',
			'description',
			'fork',
			'url',
			'langs',
			'commits',
			'nbr_commits',
			'created_at',
			'updated_at',
			'pushed_at',
			'git_url',
			'clone_url'
		];
		if(Auth::check()){
			$user = Auth::user();
			$this->user = $user;
			$this->username = $user->nickname;

			if( preg_match('/^[0-9]+$/', $this->user->social_id)	){
				$res = APIGITHUB::getRepo($this->username, $name);
				if( !isset($res->id) || empty($res->id)){
					return response()->json($res);
				}
				$res->langs = APIGITHUB::getRepoLanguages($res->url);
				$res->commits = APIGITHUB::getRepoCommits($res->url);
				$res->nbr_commits = count($res->commits);

				foreach ($keys as $key => $value) {
					$result[$value] = $res->$value;
				}
				return response()->json($result);
			}else{
				$res = APIBITBUCKET::getRepo($this->username, $name);
				if( !isset($res->id) || empty($res->id)){
					return response()->json($res);
				}
				$res->langs = APIBITBUCKET::getRepoLanguages($res->url);
				$res->commits = APIBITBUCKET::getRepoCommits($res->url);
				$res->nbr_commits = count($res->commits);

				foreach ($keys as $key => $value) {
					$result[$value] = $res->$value;
				}
				return response()->json($result);
			}
		}else{
			return response()->json(['code' => 401, 'message' => 'Unauthorised'], 401);
		}
	}
}
