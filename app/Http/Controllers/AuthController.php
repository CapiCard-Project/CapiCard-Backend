<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// persolanizar la validacion de la peticion
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function create_user(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $request->image,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 201,
            'messasge' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken

        ], 201);

    }
    
    public function login(LoginUserRequest $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        } 
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => 200,
            'messages' => 'Login successful',
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }

    /**
     * Obtener e ususario autenticado
     */
    public function user() 
    {
        $user = Auth::user();

        if(empty($user)){
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }else {
            return response()->json([
                'status' => 200,
                'user' => $user
            ]); 
        }
    }
}
