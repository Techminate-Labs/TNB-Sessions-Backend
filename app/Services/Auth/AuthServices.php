<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;

//Repository

//Services
use App\Services\BaseServices;

//Models

class AuthServices extends BaseServices{

    public function register($request){
        return $request;
        $this->logCreate($request);
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = $this->baseRI->storeInDB(
            $this->userModel,
            [
                'role_id' => 1,
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password'])
            ]
        );

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login($request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = $this->filterRI->filterBy1PropFirst($this->userModel, $fields['email'], 'email');

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Email or Password Did Not Match!'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}