<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ApiBitbucketController extends Controller
{
  const URL = 'https://api.bitbucket.org/2.0';

  static function curlUrlRequest($url)
  {
    $headers = array(
        'Pragma: no-cache',
        'Cache-Control: no-cache',
        'Accept: application/json',
        'Authorization: Bearer '.Auth::user()->api_token
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
    $url = self::URL .'/repositories/'. $user;
    $response = self::curlUrlRequest($url);
    return json_decode($response['CURLRESPONSE']);
  }

  static function getRepo($user, $name)
  {
    $url = self::URL . '/repositories/'. $user .'/'.$name;
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
