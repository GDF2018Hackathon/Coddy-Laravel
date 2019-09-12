<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/project';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();
        //$user->token;
    }

    public function redirectToProviderBitbucket()
       {
           return Socialite::driver('bitbucket')->redirect();
       }

       /**
        * Obtain the user information from GitHub.
        *
        * @return \Illuminate\Http\Response
        */
       public function handleProviderCallbackBitbucket()
       {
           $user = Socialite::driver('bitbucket')->user();

           // $user->token;
       }

    public function login(Request $request)
    {
      //  $this->validateLogin($request);

        $id = User::where('social_id', $request["social_id"])->get()->toArray();
        if (sizeOf($id) > 0) {
          $userExist = User::find($id[0]["id"]);
          Auth::login($userExist);
          redirect('/project');
        }

        // if ($this->attemptLogin($request)) {
        //     $user = $this->guard()->user();
        //     $user->generateToken();
        //
        //     return response()->json([
        //         'data' => $user->toArray(),
        //     ]);
        // }
        return response()->json(['error' => 'Unauthenticated'], 401);

        return $this->sendFailedLoginResponse($request);
    }
    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->remember_token = null;
            $user->api_token = null;
            $user->save();
        }
        Auth::logout();
        return response()->json(['data' => 'User logged out.'], 200);
    }

    public function nickname(){
      return $this->nickname;
    }
}
