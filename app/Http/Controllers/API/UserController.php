<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
       $id = User::where('email', $me->getEmail())->get()->toArray();
       $userExist = User::find($id[0]["id"]);
       $userExist->generateToken();
       $userExist->save();
       Auth::loginUsingId($id[0]["id"]);
        if(Auth::check()){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
       $user = Socialite::driver('github')->user();
       $token = $user->token;
       $me = Socialite::driver('github')->userFromToken($token);
       $temp = User::where('social_id', $me->getId())->get()->toarray();
        if (empty($temp)) {
          $user = new User;
          $user->nickname = $me->getNickname();
          if ($me->getName() == null) {
            $user->name = "";
          }else {
            $user->name = $me->getName();
          }
          $user->email = $me->getEmail();
          $user->social_id = $me->getId();
          $user->newsletter = false;
          $user->api_token = $token;
          $user->save();
          Auth::login($user);
          $success['token'] =  $user->createToken('MyApp')->accessToken;
          $success['name'] =  $user->name;
          return response()->json(['success'=>$success], $this->successStatus);
        }else {
          $id = User::where('social_id', $me->getId())->get()->toArray();
          $userExist = User::find($id[0]["id"]);
          $userArray = $userExist->toArray();
           if(!empty($userArray)){
             Auth::login($userExist, true);
               $user = Auth::user();
               $userExist->api_token = $token;
               $userExist->save();
               $success['token'] =  $user->createToken('MyApp')->accessToken;
               return response()->json(['success' => $success], $this->successStatus);
           }
           else{
               return response()->json(['error'=>'Unauthorised'], 401);
           }
        }

    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerbitbucket(Request $request)
    {
       $user = Socialite::driver('bitbucket')->user();
       $token = $user->token;
       $me = Socialite::driver('bitbucket')->userFromToken($token);
       $temp = User::where('social_id', $me->getId())->get()->toarray();
        if (empty($temp)) {
          $user = new User;
          $user->nickname = $me->getNickname();
          if ($me->getName() == null) {
            $user->name = "";
          }else {
            $user->name = $me->getName();
          }
          $user->email = $me->getEmail();
          $user->social_id = $me->getId();
          $user->api_token = $token;
          $user->newsletter = false;
          $user->save();
          Auth::login($user);
          $success['token'] =  $user->createToken('MyApp')->accessToken;
          $success['name'] =  $user->name;
          return response()->json(['success'=>$success], $this->successStatus);
        }else {
          $id = User::where('social_id', $me->getId())->get()->toArray();
          $userExist = User::find($id[0]["id"]);
          $userArray = $userExist->toArray();
          $userExist->api_token = $token;
          $userExist->save();
           if(!empty($userArray)){
             Auth::login($userExist, true);
               $user = Auth::user();
               $success['token'] =  $user->createToken('MyApp')->accessToken;
               return response()->json(['success' => $success], $this->successStatus);
           }
           else{
               return response()->json(['error'=>'Unauthorised'], 401);
           }
        }

    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        if($user != null){
          return response()->json(['success' => $user], $this->successStatus);
        }else {
          return response()->json(["code"=>403 ,'message'=>"You don't have permission to access"], 403);
        }
    }
}
