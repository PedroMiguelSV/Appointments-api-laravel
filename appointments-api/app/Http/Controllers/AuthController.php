<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'email.email' => 'El correo electrónico no es válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60 
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al intentar registrar el usuario.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Las credenciales son incorrectas.'], 401);
        }
    
        return response()->json([
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'error' => 'Ocurrió un error al intentar hacer login.'
        ], 500);
        }
    }
    
    public function me()
    {
        try {
            $user = JWTAuth::user();
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Sesión cerrada correctamente.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión.'], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh();
            return response()->json([
                'token' => $newToken,
                'expires_in' => JWTAuth::factory()->getTTL() * 60 
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo refrescar el token.'], 500);
        }
    }

    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado exitosamente.'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    }
}
