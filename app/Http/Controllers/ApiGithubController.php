<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiGithubController extends Controller
{
	const URL = 'https://api.github.com';

	static function curlUrlRequest($url)
	{
		$headers = array(
		    'Pragma: no-cache',
		    'Cache-Control: no-cache',
		    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/604.3.5 (KHTML, like Gecko) Version/11.0.1 Safari/604.3.5',
	    );

		$REQUEST = curl_init($url);

		curl_setopt($REQUEST, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($REQUEST, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($REQUEST, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($REQUEST, CURLOPT_TIMEOUT, 30);
		curl_setopt($REQUEST, CURLOPT_CUSTOMREQUEST, 'GET');

		$CURLRESPONSE = curl_exec($REQUEST);
		$INFORESPONSE = curl_getinfo($REQUEST);
		$CURLERRNO = curl_errno($REQUEST);

		curl_close($REQUEST);

		return [
			'CURLRESPONSE' => $CURLRESPONSE,
			'INFORESPONSE' => $INFORESPONSE,
			'CURLERRNO' => $CURLERRNO
		];
	}

	static function getRepos($user)
	{
		$url = self::URL . '/users/'. $user .'/repos';
		$response = self::curlUrlRequest($url);
		if( isset($response['CURLRESPONSE']) && !empty($response['CURLRESPONSE']) && $response['CURLRESPONSE'] != false && $response['CURLRESPONSE'] != NULL  ){
			return response()->json($response['CURLRESPONSE']);
		}else{
			return response()->json(['code' => 500, 'message' => 'Error on curl response'], 500);
		}
	}

	static function getRepo($user, $name)
	{
		$url = self::URL . '/repos/'. $user .'/'.$name;
		$response = self::curlUrlRequest($url);
		return response()->json($response['CURLRESPONSE']);
	}

	static function getRepoLanguages($url)
	{
		$url = $url .'/languages';
		$response = self::curlUrlRequest($url);
		return response()->json($response['CURLRESPONSE']);
	}

	static function getRepoCommits($url)
	{
		$url = $url .'/commits';
		$response = self::curlUrlRequest($url);
		return response()->json($response['CURLRESPONSE']);
	}
}
