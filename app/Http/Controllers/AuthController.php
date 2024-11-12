<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// persolanizar la validacion de la peticion
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
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

    /**
     * Actualizar la imagen de perfil del usuario
     */
    public function updated_image(Request $request)
    {
        $user = Auth::user();

        $validated = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error al validar la imagen',
                'errors' => $validated->errors()
            ], 400);
        }

        if ($request->hasFile('image')) {
            // guarda la imagen en la ruta
            $path = $request->file('image')->store('images', 'public');

            // generar la url
            $url = Storage::url($path);
            
            //asignamos la url de la imagen al usuario
            $user->image = $url;
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Image updated successfully',
                'user' => env('APP_URL').$url
            ]);
        }
    }
}
