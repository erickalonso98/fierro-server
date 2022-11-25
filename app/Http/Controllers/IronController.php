<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Iron;
use App\Helpers\JwtAuth;

class IronController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index','show','getImage','totalHigh']]);
    }

    public function index(){
        $irons = Iron::all();

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'irons'  => $irons
        ]);
    }

    public function show($id){
        $iron = Iron::find($id);

        if(is_object($iron)){
            $data = [
                'status' => 'success',
                'code'   => 200,
                'iron'   => $iron
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no existe el fierro'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            $validator = \Validator::make($params_array,[
                'person_id'    => 'required',
                'iron_type_id' => 'required',
                'high_iron_id' => 'required',
                'name'         => 'required',
                'num_iron'     => 'required',
                'brand'        => 'required',
                'num_library'  => 'required',
                'num_foja'     => 'required',
                'image'        => 'required',
                'year'         => 'required',
                'validity'     => 'required',
                'limite'       => 'required'
            ]);

            if($validator->fails()){
                $data = [
                    'status'  => 'error',
                    'code'    => 404,
                    'message' => 'Llene los campos'
                ];
            }else{
                $iron = new Iron();

                $iron->person_id = $params->person_id;
                $iron->iron_type_id = $params->iron_type_id;
                $iron->high_iron_id = $params->high_iron_id;
                $iron->name = $params->name;
                $iron->num_iron = $params->num_iron;
                $iron->brand = $params->brand;
                $iron->num_library = $params->num_library;
                $iron->num_foja = $params->num_foja;
                $iron->image = $params->image;
                $iron->year = $params->year;
                $iron->validity = $params->validity;
                $iron->limite = $params->limite;

                $iron->save();

                $data = [
                    'status' => 'success',
                    'code'   => 200,
                    'iron'   => $iron
                ];
            }

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Introduce Datos correctos'
            ];
        }

        return response()->json($data,$data['code']);
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
                'person_id'    => 'required',
                'iron_type_id' => 'required',
                'high_iron_id' => 'required',
                'name'         => 'required',
                'num_iron'     => 'required',
                'brand'        => 'required',
                'num_library'  => 'required',
                'num_foja'     => 'required',
                'image'        => 'required',
                'year'         => 'required',
                'validity'     => 'required',
                'limite'       => 'required'
            ]);

            if($validator->fails()){
                $data['errors'] = $validator->errors();
                return response()->json($data,$data['code']);
            }else{
                unset($params_array['id']);
                unset($params_array['person_id']);
                unset($params_array['high_iron_id']);
                unset($params_array['created_at']);
                unset($params_array['updated_at']);

                $iron = Iron::where('id',$id)->update($params_array);

                $data = [
                    'status'   => 'success',
                    'code'     => 200,
                    'iron' => $params_array
                ];
            }
        }

        return response()->json($data,$data['code']);
    }

    public function upload(Request $request){
        $image = $request->file('file0');

        $validator = \Validator::make($request->all(),[
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        if(!$image || $validator->fails()){
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'error al subir la imagen',
                'errors'  => $validator->errors()
            ];
        }else{
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('iron')->put($image_name,\File::get($image));

            $data = [
                'status' => 'success',
                'code'   => 200,
                'image'  => $image_name
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function getImage($filename){
        $isset = \Storage::disk('iron')->exists($filename);

        if($isset){
            $file = \Storage::disk('iron')->get($filename);
            return new Response($file,200);
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'error la imagen no existe'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function destroy($id){

        $iron = Iron::find($id);

        if(!empty($iron)){

            $iron->delete();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'iron'   => $iron
            ];

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'el fierro no existe'
            ];
        }

        return response()->json($data,$data['code']);
    }
/*
    public function totalHigh(){
        $iron = Iron::withSum('high','amount')->get();

        return response()->json([
            "total" => $iron
        ]);
    }
    */
}
