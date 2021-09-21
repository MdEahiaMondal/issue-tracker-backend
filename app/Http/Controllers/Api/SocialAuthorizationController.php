<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthorizationController extends Controller
{
    public function redirectToProvider($provider)
    {
//        return Socialite::driver($provider)->stateless()->redirect(); // normal
        $res =  Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(); // vue or react or ...spa
        return response()->json([
            "success" => (bool)$res,
            "message" => '',
            "data" => ["url" => $res]
        ]);
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        if (!$user->token) {
            return response()->json([
                'success' => false,
                "message" => "something is wrong please try again later"
            ]);
        }

        $appUser = User::whereEmail($user->email)->first();

        $password = Hash::make(12345678);
        if (!$appUser) {
            // create a new user
            $appUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
            ]);
            $developerRole = Role::developer()->first();
            $appUser->roles()->attach($developerRole->id);
            $appUser->socialAccounts()->create([
                'provider' => $provider,
                'provider_user_id' => $user->id,
            ]);
        } else {
            $socialAccount = $appUser->socialAccounts()->where('provider', $provider)->first();
            if (!$socialAccount) {
                $appUser->socialAccounts()->create([
                    'provider' => $provider,
                    'provider_user_id' => $user->id,
                ]);
            }
        }

        $grandToken = Client::where('password_client', 1)->first();
        $params = [
            'grant_type' => 'password',
            'client_id' => $grandToken->id,
            'client_secret' => $grandToken->secret,
            'username' => "mdeahiyakhan@gmail.com",
            'password' => "12345678",
            'scope' => '*',
        ];
        $token_request = Request::create('/oauth/token', 'post', $params);

        return response()->json([
            "success" => true,
            "data" => app()->handle($token_request)
        ], 200);
    }
}
