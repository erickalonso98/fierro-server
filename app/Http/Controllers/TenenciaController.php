<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Tenencia;

class TenenciaController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $tenencias = Tenencia::all();
        return response()->json([
            'code'     => 200,
            'status'   => 'success',
            'tenencias' => $tenencias
        ]);
    }

    public function show($id){
        $tenencia = Tenencia::find($id);

        if(is_object($tenencia)){
            $data = [
                'code'     => 200,
                'status'   => 'success',
                'tenencia' => $tenencia
            ];
        }else{
            $data = [
                'code'     => 404,
                'status'   => 'error',
                'message'  => 'no se encuentra la tenencia de la tierra'
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
                'message' => 'error al registrar la tenencia de tierra'
            ];
        }else{
            
            $tenencia = new Tenencia();
            $tenencia->name = $params_array['name'];
            $tenencia->save();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'tenencia' => $tenencia
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request,$id){
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

                $tenecia = Tenencia::where('id',$id)->update($params_array);

                $data = [
                    'status'   => 'success',
                    'code'     => 200,
                    'tenencia' => $tenecia,
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

    public function destroy(Request $request,$id){
        
        $tenencia = Tenencia::find($id);

        if(is_object($tenencia)){

            $tenencia->delete();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'tenencia' => $tenencia
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'no se puede eliminar la tenencia'
            ];
        }

        return response()->json($data,$data['code']);
    }

    
}
