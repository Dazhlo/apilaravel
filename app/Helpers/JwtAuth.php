<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key; 
use App\Models\User;

class JwtAuth
{
    public $key;

    public function __construct()
    {
        $this->key = 'esta-es-mi-clave-secreta-*2626-muy-pro-para-la-app';
    }

    public function signup($email, $password, $getToken = null)
    {
        $user = User::where("email", $email)->first();

        if ($user && password_verify($password, $user->password)) {
            
          
            $token = array(
                'sub'   => $user->id,
                'email' => $user->email,
                'name'  => $user->name,
                'iat'   => time(),
                'exp'   => time() + (7 * 24 * 60 * 60) // Expira en 1 semana
            );

          
            $jwt = JWT::encode($token, $this->key, 'HS256');

            if (is_null($getToken) || $getToken == 'false') {
                return $jwt;
            } else {
                $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
                return $decoded;
            }

        } else {
            return [
                'status'  => 'error',
                'message' => 'Login ha fallado (datos incorrectos)'
            ];
        }
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;
        try {
          
            $jwt = str_replace('"', '', $jwt);
            
        
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        } catch (\Exception $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        }

        if ($getIdentity && $auth) {
            return $decoded;
        }

        return $auth;
    }
}