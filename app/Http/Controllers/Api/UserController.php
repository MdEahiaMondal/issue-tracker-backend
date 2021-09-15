<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function password(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            "newPassword" => 'required'
        ]);
        $user = User::find(Auth::id());
        if ($user) {
            if (Hash::check($request->oldPassword, $user->password)) {
                $user->password = Hash::make($request->newPassword);
                $user->save();
                return response()->json([
                    "success" => true,
                    "message" => "Successfully password change"
                ], 200);
            }
            return response()->json([
                "success" => false,
                "message" => "Password Does not match"
            ], 404);

        } else {
            return response()->json([
                "success" => false,
                "message" => "Not found"
            ], 404);
        }
    }
    public function changeDetails(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            return response()->json([
                "success" => true,
                "message" => "Successfully change",
                "data" => new UserResource($user)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Not found"
            ], 404);
        }
    }
}
