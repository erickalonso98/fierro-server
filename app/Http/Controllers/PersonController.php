<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Person;
use App\Models\Iron;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth', ['except' => ['index',
                                                    'show',
                                                    'getImage',
                                                    'reportIne',
                                                    'reportCurp',
                                                    'reportRfc',
                                                    'reportName']]);
    }

    public function index(Request $request){
        $persons = Person::all();

        if(is_object($persons)){
            $data = [
                'status' => 'success',
                'code'   => 200,
                'persons'   => $persons
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'ninguna persona registrada'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function show($id){

        $person = Person::find($id)->load('property')->load('iron')->load('state');

        if(!empty($person)){

            $data = [
                'status' => 'success',
                'code'   => 200,
                'person' => $person
            ];

        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'no se encuentra ningun ciudadano'
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json,true);

        if(!empty($params_array)){
            //autenticar al usuario quien registra al ciudadano 
            $user = $this->getIdentity($request);

            $validator = \Validator::make($params_array,[
                'name'           => 'required',
                'surname'        => 'required',
                'lastname'       => 'required',
                'state_id'       => 'required',
                'code_postal'    => 'required',
                'curp'           => 'required',
                'rfc'            => 'required',
                'ine'            => 'required',
                'age'            => 'required',
                'image'          => 'required',
                'phone'          => 'required',
                'email'          => 'required|email|unique:datapersons'
            ]);

            if($validator->fails()){
                    $data = [
                        'status'  => 'error',
                        'code'    => 404,
                        'message' => 'No se ha creado ala persona correctamente llena los datos requiridos'
                    ];
            }else{
                
                $person = new Person();
                $person->user_id = $user->sub;
                $person->name = $params->name;
                $person->surname = $params->surname;
                $person->lastname = $params->lastname;
                //$person->description = $params->description;
                $person->state_id = $params->state_id;
                $person->code_postal = $params->code_postal;
                $person->curp = $params->curp;
                $person->rfc = $params->rfc;
                $person->ine = $params->ine;
                $person->age = $params->age;
                $person->image = $params->image;
                $person->phone = $params->phone;
                $person->email = $params->email;

                $person->save();

                $data = [
                    'status' => 'success',
                    'code'   => 200,
                    'person' => $person
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
                'name'           => 'required',
                'surname'        => 'required',
                'lastname'       => 'required',
                'description'    => 'required',
                'state_id'       => 'required',
                'code_postal'    => 'required',
                'curp'           => 'required',
                'rfc'            => 'required',
                'ine'            => 'required',
                'age'            => 'required',
                'image'          => 'required',
                'phone'          => 'required',
                'email'          => 'required|email'
            ]);

            if($validator->fails()){
                $data['errors'] = $validator->errors();
                return response()->json($data,$data['code']);
            }else{
                //datos que no se vana actualizar
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);

                $user = $this->getIdentity($request);
                //Buscar el registro
                $person = Person::where('id',$id)
                        ->where('user_id',$user->sub)
                        ->first();

                if(!empty($person) && is_object($person)){

                    $person->update($params_array);

                    $data = [
                        'status'  => 'success',
                        'code'    => 200,
                        'person'  => $person,
                        'changes' => $params_array
                    ];
                }else{
                    $data = [
                        'status'  => 'error',
                        'code'    => 404,
                        'message' => 'No se realizo la actualizacion del registro'
                    ];
                }

                /*
                $where = [
                    'id'      => $id,
                    'user_id' => $user->sub 
                ];

                $person = Person::updateOrCreate($where,$params_array); */
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

            \Storage::disk('person')->put($image_name, \File::get($image));

            $data = [
                'status' => 'success',
                'code'   => 200,
                'image'  => $image_name
            ];

        }

        return response()->json($data,$data['code']);
    }

    public function getImage($filename){
        $isset = \Storage::disk('person')->exists($filename);

        if($isset){
            $file = \Storage::disk('person')->get($filename);
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

    public function destroy($id, Request $request){
        //conseguir al usuario identificado
        $user = $this->getIdentity($request);

        $person = Person::where('id',$id)
                ->where('user_id',$user->sub)
                ->first();

        if(!empty($person)){

            $person->delete();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'person' => $person
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 400,
                'message' => 'error al eliminar un registro'
            ];
        }

        return response()->json($data,$data['code']);
    }

    private function getIdentity($request){

        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization',null);
        $user = $jwtAuth->checkToken($token,true);

        return $user;
    }

    //reporte por ine
    public function reportIne($ine){
        /*
        $person = Person::select('datapersons.*')
                            ->join('property','property.person_id','=','datapersons.id')
                            ->join('iron','iron.person_id','=','datapersons.id')
                            ->where('ine',$ine)
                            ->first();
            $person = Person::whereRelation('property','ine',$ine)->get();
        */
        
        $person = Person::with('property')
                        ->with('iron')
                        ->with('state')
                        ->where('ine',$ine)
                        ->first();

        if(!empty($person) || is_object($person)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "person" => $person
             ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "La persona con el ine no existe"
             ];
        }

        return response()->json($data,$data["code"]);
    }

    //reporte por curp
    public function reportCurp($curp){
        /*
        $person = Person::join('property','property.person_id','=','datapersons.id')
                        ->join('iron','iron.person_id','=','datapersons.id')
                        ->where('curp',$curp)
                        ->first();
        */

        $person = Person::with('property')
                        ->with('iron')
                        ->with('state')
                        ->where('curp',$curp)
                        ->first();

        if(!empty($person) || is_object($person)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "person" => $person
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "La persona con la curp no existe"
             ];
        }

        return response()->json($data,$data["code"]);
    }

    //reporte por rfc
    public function reportRfc($rfc){
        /*
        $person = Person::join('property','property.person_id','=','datapersons.id')
                        ->join('iron','iron.person_id','=','datapersons.id')
                        ->where('rfc',$rfc)
                        ->first();
      */

      $person = Person::with('property')
                        ->with('iron')
                        ->with('state')
                        ->where('rfc',$rfc)
                        ->first();

         if(!empty($person) || is_object($person)){
                $data = [
                    "status" => "success",
                    "code"   => 200,
                    "person" => $person
                ];
            }else{
                $data = [
                    "status"  => "error",
                    "code"    => 404,
                    "message" => "La persona con rfc no existe"
                 ];
            }

            return response()->json($data,$data["code"]);
    }

    //reporte por nombre
    public function reportName($name){
        $person = Person::with('property')
                        ->with('iron')
                        ->with('state')
                        ->where('name',$name)
                        ->first();

        if(!empty($person) || is_object($person)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "person" => $person
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "El nombre de la persona no existe"
             ];
        }
        
        return response()->json($data,$data["code"]);
    }
   
}
