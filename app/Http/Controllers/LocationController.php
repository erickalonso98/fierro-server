<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Location;
use App\Models\State;
use App\Models\Municipalitie;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show','ModifyState','getStateMunicipalitie','getMunicipalitieLocation']]);
    }

    public function index(){
        $locations = Location::all();

        $data = [
            "status"   => "success",
            "code"     => 200,
            "locations" => $locations
        ];

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $location = Location::find($id);

        if(!empty($location) || is_object($location)){
            $data = [
                'status'   => 'success',
                'code'     => 200,
                'location' => $location
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no se encuentra la localidad del municipio'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'name'             => 'required',
            'municipalitie_id' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => $validator->errors()
            ];
        }else{
            $location = new Location();
            $location->name = $params_array['name'];
            $location->municipalitie_id = $params_array['municipalitie_id'];
            $location->save();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'location' => $location
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request, $id){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            $validator = \Validator::make($params_array,[
                'name'             => 'required',
                'municipalitie_id' => 'required'
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
                $location = Location::where('id',$id)->update($params_array);

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

        return response()->json($data,$data["code"]);
    }

    public function destroy(Request $request, $id){
        $location = Location::find($id);

        if(!empty($location) || is_object($location)){
            $location->delete();

            $data = [
                'status'   => 'success',
                'code'     => 200,
                'location' => $location
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'error no se puede eliminar la localidad del municipio'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function ModifyState(){
        
        $location = Location::select('location.id','location.municipalitie_id','municipalities.id','municipalities.state_id')
                                ->join('municipalities','location.municipalitie_id','=','municipalities.id')
                                ->get();
        
        if(!empty($location) || is_object($location)){
            $data = [
                "status"       => "success",
                "code"         => 200,
                "modify_state" => $location
             ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "no se encuentra la relacion"
             ];
        }
        
        return response()->json($data,$data["code"]);
    }

    public function getStateMunicipalitie($state_id){
        $municipalitie = Municipalitie::select('municipalities.id','municipalities.name')
                                        ->where('municipalities.state_id',$state_id)
                                        ->get();

        if(!empty($municipalitie) || is_object($municipalitie)){
            $data = [
                "status"              => "success",
                "code"                => 200,
                "municipalitie_state" => $municipalitie
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra la relacion"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function getMunicipalitieLocation($municipalitie_id){
        $location = Location::select('location.id','location.name')
                            ->where('location.municipalitie_id',$municipalitie_id)
                            ->get();

        if(!empty($location) || is_object($location)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "municipalitie_location" => $location
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "no se encuentra la relacion"
            ];
        }

        return response()->json($data,$data["code"]);
    }
}
