<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request){
        $user = User::all();

        if(is_object($user)){
            $data = [
                'status' => 'success',
                'code'   => 200,
                'user'   => $user
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'usuarios no existen'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function register(Request $request){
        
        $json = $request->input('json',null);
        $params = json_decode($json); //object
        $params_array = json_decode($json,true); //array

        if(!empty($params) && !empty($params_array)){

        //Limpiar los Datos de array
       // $params_array = array_map('trim',$params_array);

        $validator = \Validator::make($params_array,[
                'name'     => 'required|alpha',
                'surname'  => 'required|alpha',
                'lastname' => 'required|alpha',
                'email'    => 'required|email|unique:users',
                'password' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "Llena todos los campos Correctamente",
                "errors"  => $validator->errors()
            ];   
        }else{

        //cifrar la contraseña
        $pwd = hash('sha256',$params->password);
        //crear el usuario
        $user = new User();
        $user->name = $params_array['name'];
        $user->surname = $params_array['surname'];
        $user->lastname = $params_array['lastname'];
        $user->email = $params_array['email'];
        $user->password = $pwd;

        //Guardar el usuario
        $user->save();

        //Asignar el rol del usuario
        $user->roles()->attach($params->roles);
        

            $data = [
                "status"  => "success",
                "code"    => 200,
                "message" => "Usuario creado Correctamente",
                "user"    => $user
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

    public function login(Request $request){
        $jwt = new \JwtAuth();

        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $signup = [
                "status"  => "error",
                "code"    => 404,
                "message" => "El usuario no se ha logueado correctamente.",
                "errors"  => $validator->errors()
            ];  
        }else{
            $pwd = hash('sha256',$params->password);
            $signup = $jwt->signup($params->email,$pwd);
            
            if(!empty($params->gettoken)){
                $signup = $jwt->signup($params->email,$pwd,true);
            }
        }

        return response()->json($signup,200);
    }

    public function update(Request $request){
        //Comprobar si el usuario esta indentificado
        
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

         //Actualizar al usuario
         $json = $request->input('json',null);
         $params_array = json_decode($json,true);

        if($checkToken && !empty($params_array)){
           
          //identificar el usuario
          $user = $jwtAuth->checkToken($token,true);
        
          $validator = \Validator::make($params_array,[
            'name'     => 'required|alpha',
            'surname'  => 'required|alpha',
            'lastname' => 'required|alpha',
            'email'    => 'required|email|unique:users'.$user->sub
          ]);


          //datos que no se van a actualizar
          unset($params_array['id']);
          unset($params_array['created_at']);
          unset($params_array['password']);
          unset($params_array['remember_token']);

          $user_update = User::where('id',$user->sub)->update($params_array);
         
          $data = [
                'code'    => 200,
                'status'  => 'success',
                'user'    => $user,
                'changes' => $params_array 
          ];

        }else{
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'El usuario no se ha identificado'
            ];
        }
        return response()->json($data,$data['code']);
    }

    public function upload(Request $request){

        //recojer los datos por json
        $image = $request->file('file0');

        //validacion de imagenes
        $validator = \Validator::make($request->all(),[
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //Guardar la imagen
        if(!$image || $validator->fails()){
            
            $data = [
                'code'    => 400,
                'status'  => 'error',
                'message' => 'imagen no encontrada'
            ];

        }else{

            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name,\File::get($image));

            $data = [
                'code'   => 200,
                'image'  => $image_name,
                'status' => 'success'
            ];
        }

        //devolver el resultado
        return response()->json($data,$data['code']);  
    }

    public function getImage($filename){
        $isset = \Storage::disk('users')->exists($filename);

        if($isset){
            $file = \Storage::disk('users')->get($filename);
            return new Response($file,200);
        }else{
            $data = [
                'code'    => 404,
                'status'  => 'error',
                'message' => 'imagen no existe'
            ];

            return response()->json($data,$data['code']);  
        }
    }

    public function profile($id){
        $user = User::find($id);

        if(is_object($user)){
            $data = [
                'code'   => 200,
                'status' => 'success',
                'user'   => $user
            ];
        }else{
            $data = [
                'code'   => 404,
                'status' => 'error',
                'message'=> 'El usuario no existe'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function getUsersRoles(){
       $user = User::all()->load('roles');

       if(!empty($user) || is_object($user)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "user"   => $user
            ];
       }else{
            $data = [
                "status"  => "success",
                "code"    => 404,
                "message" => "no se encuentra ningun usuario con rol existente"
            ];
       }

       return response()->json($data,$data["code"]);
    }

}
