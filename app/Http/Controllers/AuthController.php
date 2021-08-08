<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        if (! Hash::check($request->password, optional($user)->password)) {
            abortJson('Invalid credentials supplied');
        }

        $token = $user->createToken('to-do-app-authentication-token')->plainTextToken;

        return json([
            'user' => new UserResource($user),
            'token' => $token
        ], "Authenticated");
    }
}
