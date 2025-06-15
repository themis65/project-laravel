<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //metodo para registrar un nuevo usuario
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => 'cliente', // Default role
        ]);
        return response()->json($user, 201);
    }

    //metodo para iniciar sesion
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //devolver un token de acceso si las credenciales son correctas
        if(!Auth::attempt($credentials)){
            return response()->json(['message' => 'Credenciales invalidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    //metodo para cerrar sesion
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete(); // Elimina el token actual
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
