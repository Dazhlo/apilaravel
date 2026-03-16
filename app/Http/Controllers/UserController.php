<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Index de UserController'
        ], 200);
    }

    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();
        
      
        $email = $request->input('email');
        $password = $request->input('password');
        $getToken = $request->input('getToken');

        if (!empty($email) && !empty($password)) {
          
            $signup = $jwtAuth->signup($email, $password, $getToken);
            return response()->json($signup, 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Envía tus datos por POST (email y password)'
            ], 400);
        }
    }

    public function register(Request $request)
    {
        $name     = $request->input('name');
        $email    = $request->input('email');
        $password = $request->input('password');

        if (!empty($name) && !empty($email) && !empty($password)) {
            
            
            $isset_user = User::where('email', $email)->first();

            if (!$isset_user) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                
                
              
                $user->password = password_hash($password, PASSWORD_DEFAULT);
                
                $user->save();

                $data = [
                    'status' => 'success',
                    'codigo' => 200,
                    'message' => 'Usuario Registrado correctamente'
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'codigo' => 400,
                    'message' => 'Usuario Duplicado, no puede registrarse'
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'codigo' => 400,
                'message' => 'Usuario no Creado (Datos incompletos)'
            ];
        }

        return response()->json($data, $data['codigo']);
    }
}