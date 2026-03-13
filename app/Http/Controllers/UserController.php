<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\User;
class UserController extends Controller
{

    public function index()
    {
        echo "Index de CarController";
        die();
    }
    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();
        //Recibir POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken : null;


        if (!is_null($email) && !is_null($password) && ($getToken == null || $getToken == false)) {
            $signup = $jwtAuth->signup($email, $password);
        } elseif ($getToken != null) {
            $signup = $jwtAuth->signup($email, $password, $getToken);
        } else {
            $signup = array(
                'status' => 'error',
                'message' => 'Envía tus datos por post',
            );
        }
        return response()->json($signup, 200);
    }



    public function register(Request $request)
    {
        //Recoger el post
        $json = $request->input('json', null);
        $params = json_decode($json); //Permite convertir a formato de objeto de php lo que llegue
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;


        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        if (!is_null($email) && !is_null($password) && !is_null($name)) {                //Crear el usuario
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $pwd = password_hash($password, PASSWORD_DEFAULT);
            $user->password = $pwd;
            //Comprobar usuario duplicado
            $isset_user = User::where('email', '=', $email)->get();
            if (count($isset_user) == 0) {
                //Guardar el usuario
                $user->save();
                $data = array(
                    'status' => 'success',
                    'codigo' => 200,
                    'message' => 'Usuario Registrado correctamente'
                );
            } else {
                //No guardarlo
                $data = array(
                    'status' => 'error',
                    'codigo' => 400,
                    'message' => 'Usuario Duplicado, no puede registrarse'
                );
            }
            return response()->json($data, $data['codigo']);
        } else {
            $data = array(
                'status' => 'error',
                'codigo' => 400,
                'message' => 'Usuario no Creado (Datos incompletos)'
            );
            return response()->json($data, $data['codigo']);
        }
    }


}

