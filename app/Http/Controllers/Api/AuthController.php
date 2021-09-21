<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistryRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;


class AuthController extends Controller
{
    public function login(UserLoginRequest $request){
        $grandToken = Client::where('password_client', 1)->first();
        $params = [
            'grant_type' => 'password',
            'client_id' => $grandToken->id,
            'client_secret' => $grandToken->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];
        $token_request = Request::create('/oauth/token', 'post', $params);
        return app()->handle($token_request);
    }
    public function register(UserRegistryRequest $request){
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());
        $developer = Role::developer()->first();

        $user->roles()->attach($developer->id);

        if ($user){
            return response()->json([
                'success' => true,
                'message' => 'Registration successfully complete.'
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Registration failed!.'
        ]);
    }
}
