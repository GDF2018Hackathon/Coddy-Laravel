<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiGithubController as APIGITHUB;

class ReposController extends Controller
{
	public $username;

	function __construct($username = null, Request $request)
	{
		$header = $request->header('Authorization');
		$token = explode(" ",$header)[1];
		$user = User::where('api_token', $token)->get()->toarray();
		if(isset($user[0]['nickname']) ){
			$this->username = $user[0]['nickname'];
		}else{
			return response()->json(['code' => 403, 'text' => 'Unauthorized access']);
		}
	}

	public function index()
	{
		return response()->json(APIGITHUB::getRepos($this->username));
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
		$res = APIGITHUB::getRepo($this->username, $name);

		if( !isset($res->id) || empty($res->id)){
			return response()->json($res);
		}


		$res->langs = APIGITHUB::getRepoLanguages($res->url);
		$res->commits = APIGITHUB::getRepoCommits($res->url);
		$res->nbr_commits = count($res->commits);


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

		foreach ($keys as $key => $value) {
			$result[$value] = $res->$value;
		}

		return response()->json($result);
		// return response()->json($res);
	}
}
