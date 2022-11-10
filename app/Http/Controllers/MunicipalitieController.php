<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Municipalitie;

class MunicipalitieController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $municipalitie = Municipalitie::all();

        $data = [
            'status'        => 'success',
            'code'          => 200,
            'municipalitie' => $municipalitie
        ];

        return response()->json($data,$data['code']);
    }

    public function show($id){
        $municipalitie = Municipalitie::find($id);

        if(!empty($municipalitie) || is_object($municipalitie)){
            $data = [
                'status'         => 'success',
                'code'           => 200,
                'municipalitie'  => $municipalitie
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no se encuentra el municipio del estado'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'name'     => 'required',
            'state_id' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => $validator->errors()
            ];
        }else{
            $municipalitie = new Municipalitie();
            $municipalitie->name = $params_array['name'];
            $municipalitie->state_id = $params_array['state_id'];
            $municipalitie->save();

            $data = [
                'status'        => 'success',
                'code'          => 200,
                'municipalitie' => $municipalitie
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request, $id){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            $validator = \Validator::make($params_array,[
                'name'     => 'required',
                'state_id' => 'required'
            ]);

            unset($params_array['id']);
            unset($params_array['created_at']);

            if($validator->fails()){
                $data = [
                    'status'  => 'error',
                    'code'    => 404,
                    'message' => 'Introduce Datos Correctos',
                    'errors'  => $validator->errors()
                ];
            }else{

                $municipalitie = Municipalitie::where('id',$id)->update($params_array);

                $data = [
                    'status'  => 'success',
                    'code'    => 200,
                    'changes' => $params_array
                ];
            }

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no hay datos que actualizar'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function destroy(Request $request , $id){
        $municipalitie = Municipalitie::find($id);

        if(!empty($municipalitie) || is_object($municipalitie)){
            $municipalitie->delete();

            $data = [
                'status'        => 'success',
                'code'          => 200,
                'municipalitie' => $municipalitie
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'error no se puede eliminar el municipio del estado'
            ];
        }

        return response()->json($data,$data['code']);
    }
}
