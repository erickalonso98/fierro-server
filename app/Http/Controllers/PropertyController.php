<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Property;
use App\Helpers\JwtAuth;

class PropertyController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index','show','getImage']]);
    }

    public function index(){
        $propertys = Property::all()->load('exploitation')->load('tenencia');

        $data = [
            'status'    => 'success',
            'code'      => 200,
            'propertys' => $propertys
        ];

        return response()->json($data,$data['code']);
    }
    
    public function show($id){

        $property = Property::find($id)->load('exploitation')->load('tenencia');

        if(is_object($property)){
            $data = [
                'status'   => 'success',
                'code'     => 200,
                'property' => $property
            ];
        }else{
            $data = [
                'status'   => 'error',
                'code'     => 404,
                'message'  => 'No se encuentra el predial'
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
                'name'                 => 'required',
                'person_id'            => 'required',
                'exploitation_type_id' => 'required',
                'type_tenencia_id'     => 'required',
                'image'                => 'required',
                'description'          => 'required',
                'num_parcelas'         => 'required',
                'type_superficie'      => 'required'
            ]);

            if($validator->fails()){
                $data = [
                    'status'  => 'error',
                    'code'    => 404,
                    'message' => 'Llene los campos'
                ];
            }else{
                $property = new Property();

                $property->name = $params->name;
                $property->person_id = $params->person_id;
                $property->exploitation_type_id = $params->exploitation_type_id;
                $property->type_tenencia_id = $params->type_tenencia_id;
                $property->image = $params->image;
                $property->description = $params->description;
                $property->num_parcelas = $params->num_parcelas;
                $property->type_superficie = $params->type_superficie;

                $property->save();

                $data = [
                    'status'   => 'success',
                    'code'     => 200,
                    'property' => $property
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
                'name'                 => 'required',
                'person_id'            => 'required',
                'exploitation_type_id' => 'required',
                'type_tenencia_id'     => 'required',
                'image'                => 'required',
                'description'          => 'required',
                'num_parcelas'         => 'required',
                'type_superficie'      => 'required'
            ]);

            if($validator->fails()){
                $data['errors'] = $validator->errors();
                return response()->json($data,$data['code']);
            }else{
                //datos que no se van actualizar
                unset($params_array['id']);
                unset($params_array['person_id']);
                unset($params_array['created_at']);

                //actualizar el predial
                $property = Property::where('id',$id)->update($params_array);

                $data = [
                    'status'   => 'success',
                    'code'     => 200,
                    'property' => $params_array
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
            \Storage::disk('property')->put($image_name,\File::get($image));

            $data = [
                'status' => 'success',
                'code'   => 200,
                'image'  => $image_name
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function getImage($filename){
        $isset = \Storage::disk('property')->exists($filename);

        if($isset){
            $file = \Storage::disk('property')->get($filename);
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
        $property = Property::find($id);

        if(!empty($property)){

            $property->delete();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'person' => $property
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'error al eliminar un registro'
            ];
        }

        return response()->json($data,$data['code']);
    }


}
