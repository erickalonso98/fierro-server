<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Role;

class RoleController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $roles = Role::all();

        $data = [
            "status" => "success",
            "code"   => 200,
            "roles"  => $roles
        ];

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $role = Role::find($id);

        if(!empty($role) || is_object($role)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "role"  => $role
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra el rol del usuario"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        if(!empty($params) && !empty($params_array)){
            $validator = \Validator::make($params_array,[
                "name" => "required"
            ]);

            if($validator->fails()){
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "Llena todos los campos Correctamente requeridos",
                    "errors"  => $validator->errors()
                ];  
            }else{
                $role = new Role();

                $role->name = $params_array['name'];
                $role->save();

                $data = [
                    "status"  => "success",
                    "code"    => 200,
                    "message" => "Rol del usuario creado Correctamente",
                    "role"    => $role
                ];
             }

        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "los datos enviados no son correctos"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function update(Request $request, $id){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $data = [
            'status'  => 'error',
            'code'    => 400,
            'message' => 'error al actualizar los datos'
        ];

        if(!empty($params_array)){
            
            $validator = \Validator::make($params_array,[
                "name" => "required"
            ]);

            if($validator->fails()){
                $data["errors"] = $validator->errors();
                return response()->json($data,$data["code"]);
            }else{
                unset($params_array["id"]);
                unset($params_array["created_at"]);

                $role = Role::where('id',$id)->update($params_array);

                $data = [
                    "status"  => "success",
                    "code"    => 200,
                    "changes" => $params_array 
                ];
            }
        }

        return response()->json($data,$data["code"]);
    }

    public function destroy(Request $request, $id){
        $role = Role::find($id);

        if(!empty($role) || is_object($role)){
            $role->delete();

            $data = [
                "status" => "success",
                "code"   => 200,
                "role"   => $role
            ];

        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "error al eliminar un rol que no existe"
            ];
        }

        return response()->json($data,$data["code"]);
    }
}
