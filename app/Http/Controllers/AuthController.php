<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// persolanizar la validacion de la peticion
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    /**
     * Crear un nuevo usuario
     * @param CreateUserRequest $request
     */
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

        ], 201);

    }

    /**
     * Login de usuario
     * @param LoginUserRequest $request
     */
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
     * actualizar coints de usuario
     */
    public function update_coins(Request $request)
    {
        $user = Auth::user();
        $user->capipoins = $request->input('capiPoints');
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Coins updated successfully',
            'user' => $user
        ]);
    }
}
