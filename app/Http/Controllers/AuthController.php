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
    /**
     * register a new user
     *
     * @param UserRegisterRequest $request
     * @return Response
     */
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'email_verified_at' => $request['email_verified_at'],
            'remember_token' => $request['remember_token'],
        ]);

        if(!$user) {
            return Response([
                'message' => 'User not created.'
            ], 415);
        }

        $token = $user->createToken('beenToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => $user['email'] . ' successfully registered.'
        ];

        return Response($response, 201);
    }

    /**
     * login a user
     *
     * @param UserLoginRequest $request
     * @return Response
     */
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

        return response($response, 200); 
    }

    /**
     * logout the user
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return Response ([
            'message' => 'logged out',
            200
        ]);
    }
}
