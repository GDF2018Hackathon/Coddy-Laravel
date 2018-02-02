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
		return json_decode($response['CURLRESPONSE']);
	}

	static function getRepo($user, $name)
	{
		$url = self::URL . '/repos/'. $user .'/'.$name;
		$response = self::curlUrlRequest($url);
		return json_decode($response['CURLRESPONSE']);
	}

	static function getRepoLanguages($url)
	{
		$url = $url .'/languages';
		$response = self::curlUrlRequest($url);
		return json_decode($response['CURLRESPONSE']);
	}

	static function getRepoCommits($url)
	{
		$url = $url .'/commits';
		$response = self::curlUrlRequest($url);
		return json_decode($response['CURLRESPONSE']);
	}
}
