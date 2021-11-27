<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken('beenToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201); 
    }

    public function login(UserLoginRequest $request)
    {
        // check if user exists
        $user = User::where('email', $request['email'])->first();

        if(!$user) {
            return Response([
                'message' => 'That User not registered'
            ]);
        }
        if(!Hash::check($request['password'], $user->password)) {
            return Response([
                'message' => 'invalid credentials'
            ], 401);
        }

        $token = $user->createToken('beenToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => $user['email'] . ' successfully logged in'
        ];

        return response($response, 201); 
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response ([
            'message' => 'logged out'
        ]);
    }
}
