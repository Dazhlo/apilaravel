<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\Validator;
class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request){
  $hash =  $request->header('Authorization', null);
   $jwtAuth = new JwtAuth();
   $checkToken = $jwtAuth->checkToken($hash);
   if($checkToken){
   	$cars = Car::all();
   	return response()->json(array(
      	 'cars'=>$cars,
      	 'status'=>'success'
   	), 200);
  }else{
       echo "Index de CarController No Autenticado"; die();
   }

}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            //Recoger los datos por post
            $json = $request->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            //Conseguir el usuario identificado
            $user = $jwtAuth->checkToken($hash, true);

            //Validación
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required'
            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }


            //Guardar el coche
            $car = new Car();
            $car->user_id = $user->sub;
            $car->title = $params->title;
            $car->description = $params->description;
            $car->price = $params->price;
            $car->status = $params->status;
            $car->save();

            $data = array(
                'car' => $car,
                'status' => 'success',
                'code' => 200
            );

        } else {
            //Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'success',
                'code' => 200
            );
        }
        return response()->json($data, 300);
    }


    /**
     * Display the specified resource.
     */
public function show($id, Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            $car = Car::find($id);

            if (is_object($car)) {
                return response()->json([
                    'car' => $car,
                    'status' => 'success'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'El coche no existe',
                    'status' => 'error'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'No autenticado',
                'status' => 'error'
            ], 401);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update($id, Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            // Recoger datos
            $json = $request->input('json', null);
            $params_array = json_decode($json, true);

            if (!empty($params_array)) {
                // Validar datos
                $validate = Validator::make($params_array, [
                    'title'       => 'required',
                    'description' => 'required',
                    'price'       => 'required',
                    'status'      => 'required'
                ]);

                if ($validate->fails()) {
                    return response()->json($validate->errors(), 400);
                }

                // Quitar lo que no queremos actualizar (id y user_id por seguridad)
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);

                // Actualizar el carro en la DB
                $car = Car::where('id', $id)->update($params_array);

                return response()->json([
                    'car'    => $params_array, // Retornamos los datos actualizados
                    'status' => 'success',
                    'code'   => 200
                ], 200);

            } else {
                return response()->json(['message' => 'Datos vacíos', 'status' => 'error'], 400);
            }

        } else {
            return response()->json([
                'message' => 'Login incorrecto',
                'status' => 'error'
            ], 401);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id, Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            // Comprobar si existe el registro
            $car = Car::find($id);

            if (!empty($car)) {
                $car->delete();
                
                return response()->json([
                    'car'    => $car,
                    'status' => 'success',
                    'code'   => 200
                ], 200);
            } else {
                return response()->json([
                    'message' => 'El coche no existe',
                    'status' => 'error'
                ], 404);
            }

        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Login Incorrecto !!'
            ], 401);
        }
    }

}
