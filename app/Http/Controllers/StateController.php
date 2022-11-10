<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\State;

class StateController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $states = State::all();

        $data = [
            'status' => 'success',
            'code'   => 200,
            'state'  => $states
        ];

        return response()->json($data,$data['code']);
    }

    public function show($id){

        $state = State::find($id);

        if(!empty($state)){
            $data = [
                'status' => 'success',
                'code'   => 200,
                'state'  => $state
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no se encuentra el estado'
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
                'code'    => 404,
                'message' => $validator->errors()
            ];
        }else{
            $state = new State();
            $state->name = $params_array['name'];
            $state->save();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'state' => $state
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request, $id ){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            $validator = \Validator::make($params_array,[
                'name' => 'required'
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
                $state = State::where('id',$id)->update($params_array);

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

    public function destroy(Request $request, $id){

        $state = State::find($id);

        if(!empty($state) || is_object($state)){
            $state->delete();
            $data = [
                'status' => 'success',
                'code'   => 200,
                'state'  => $state
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'error no se puede eliminar el estado'
            ];
        }

        return response()->json($data,$data['code']);
    }
}
