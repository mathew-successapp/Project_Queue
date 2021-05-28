<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Auth;
use DB;

class UserController extends Controller
{
    
    // User Registration
    public function register(RegisterRequest $request){

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
            $data['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'status' => true,
                'data' => $user,
                'login_token' => $data,
                'message' => 'Account created successfully.'
            ]);
        }

    }

    // User Login
    public function login(LoginRequest $request)
    {
        if(Auth::attempt([ 'email' => request('email'), 'password' => request('password') ]))
        {
            $user = Auth::user();
            $data['token'] = $user->createToken('MyApp')->accessToken;  
            return response()->json([
                'status' => true,
                'user_info' => $user,
                'login_token' => $data,
                'message' => ''
            ]);
        }
        return response()->json(['error'=>'Unauthorised'], 422);
    }

    // Logout
    public function logout()
    {
    	$accessToken = Auth::user()->token();
		DB::table('oauth_refresh_tokens')
			->where('access_token_id', $accessToken->id)
			->update([
				'revoked' => true
			]);

        $revoke = $accessToken->revoke();
        if($revoke){
            return response()->json([
                'status' => true,
                'message' => 'Successfully Logged Out'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Logout Failed'
            ]);
        }
    }
}
