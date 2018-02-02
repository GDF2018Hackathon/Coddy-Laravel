<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
class GithubController extends Controller
{
  public function index()
  {
       $user = Socialite::driver('github')->user();
       $token = $user->token;
       $me = Socialite::driver('github')->userFromToken($token);
       $email = User::where('email', $me->getEmail())->get()->toarray();
      if (empty($email)) {
          $user = new User;
          $user->nickname = $me->getNickname();
          $user->name = $me->getName();
          $user->email = $me->getEmail();
          $user->github_id = $me->getId();
          $user->newsletter = false;
          $user->generateToken();
          $user->save();
          Auth::login($user, true);
          return response()->json([
              'data' => $user->toArray(),
          ]);

      }else {
          $id = User::where('email', $me->getEmail())->get()->toArray();
          $userExist = User::find($id[0]["id"]);
          $userExist->generateToken();
          $userExist->save();
          Auth::loginUsingId($id[0]["id"]);
          return response()->json([
              'data' => $userExist->toarray(),
          ]);


      }

  }
}
