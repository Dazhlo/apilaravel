<?php
namespace App\Helpers;


use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class JwtAuth
{
    public $key;
    public function __construct()
    {
        $this->key = 'esta-es-mi-clave-secreta-*2626';
    }
    public function signup($email, $password, $getToken = null)
    {
        //Verifica si el usuario existe
        $user = User::where("email", $email)->first();


        if (password_verify($password, $user->password)) {


            $signup = true;
        } else {


            $signup = false;
            return array('status' => 'error', 'message' => 'Login ha fallado');
        }
        //Generar el token
        $token = array(
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60)
        );
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, array('HS256'));
        if (is_null($getToken)) {
            return $jwt;
        } else {
            return $decoded;
        }
    }
    public function checkToken($jwt, $getIdentity = false)
    {
        //Comprueba que si es valido y si es true devuelve la identidad del usuario
        $auth = false;
        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));


        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }
        if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }
        if ($getIdentity) {
            return $decoded;
        }
        return $auth;
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
    public function index(Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if ($checkToken) {
            echo "Index de CarController Autenticado";
            die();
        } else {
            echo "Index de CarController No Autenticado";
            die();
        }
    }

}
