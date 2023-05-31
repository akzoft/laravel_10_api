<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password, ['Rounds' => 12]);

        $user->save();

        return response()->json([
            "status" => 200,
            "success" => true,
            "data" => $user
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        try {
            if (auth()->attempt($request->only(['email', 'password']))) {
                $user = auth()->user();

                $accessToken = $user->createToken("SECRET_KEY")->plainTextToken;

                return response()->json([
                    "status" => 200,
                    "success" => true,
                    "message" => "Utilisateur connecté.",
                    "accessToken" => $accessToken,
                    "data" => $user
                ]);
            } else {
                return response()->json([
                    "status" => 403,
                    "success" => false,
                    "message" => "Email ou mot de passe incorrect."
                ]);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
