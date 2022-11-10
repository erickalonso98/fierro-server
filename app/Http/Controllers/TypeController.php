<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $types = Type::all();

        return response()->json([
            'status'=> 'success',
            'code'  => 200,
            'types_iron' => $types
        ]);
    }

    public function show($id){
        $type = Type::find($id);

        if(is_object($type)){

            $data = [
                'status' => 'success',
                'code'   => 200,
                'type'   => $type
            ];

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'No se encuentra el tipo de fierro'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'name' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'error al registrar el tipo de fierro'
            ];
        }else{

            $type_iron = new Type();
            $type_iron->name = $params_array['name'];
            $type_iron->save();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'type_iron' => $type_iron
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update($id, Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){

            $validator = \Validator::make($params_array,[
                'name' => 'required'
            ]);

             //quitar lo que no se va actualizar
             unset($params_array['id']);
             unset($params_array['created_at']);

            if($validator->fails()){
                $data = [
                    'status'  => 'error',
                    'code'    => 400,
                    'message' => 'introduce un dato correcto'
                ];
            }else{
                $type = Type::where('id',$id)->update($params_array);

                $data = [
                    'status'   => 'success',
                    'code'     => 200,
                    'type_iron' => $type,
                    'changes'  => $params_array
                ];
            }

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'error no se actualiza correctamente'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function destroy($id){
        $type = Type::find($id);

        if(is_object($type)){
            
            $type->delete();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'type_iron' => $type
            ];

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'no se puede eliminar el tipo de fierro'
            ];
        }

        return response()->json($data,$data['code']);
    }

}
