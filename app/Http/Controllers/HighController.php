<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\High;
use Illuminate\Support\Facades\DB;

class HighController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show','totalHighMonth','totalHighDay','totalHighYear','totalHighDate']]);
    }

    public function index(){
        $highs = High::all();

        $data = [
            'status' => 'success',
            'code'   => 200,
            'highs'  => $highs
        ];

        return response()->json($data,$data['code']);
    }

    public function show($id){
        $high = High::find($id);

        if(!empty($high) || is_object($high)){
            $data = [
                'status' => 'success',
                'code'   => 200,
                'high'   => $high
            ];
        }else{
            $data = [
                'status'  => 'error',
                'code'    => 404,
                'message' => 'No se encuentra el pago de la alta del fierro'
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'invoice' => 'required|unique:high',
            'amount'  => 'required'
        ]);

        $data = [
            'status'  => 'error',
            'code'    => 404,
            'message' => 'Ingrese Datos correctos'
        ];

        if($validator->fails()){
            $data['errors'] = $validator->errors();
            return response()->json($data,$data['code']);
        }else{
            $high = new High();
            $high->invoice = $params_array['invoice'];
            $high->amount  = $params_array['amount'];

            $high->save();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'high'   => $high
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function totalHighDate($date){
        $high_total = DB::table('high')
                        ->whereDate('created_at',$date)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "high_total" => $high_total
        ];

        return response()->json($data,$data["code"]);
        
    }

    public function totalHighDay($day){
        $high_total = DB::table('high')
                        ->whereDay('created_at',$day)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "high_total" => $high_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalHighMonth($month){
        $high_total = DB::table('high')
                        ->whereMonth('created_at',$month)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "high_total" => $high_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalHighYear($year){
        $high_total = DB::table('high')
                        ->whereYear('created_at',$year)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "high_total" => $high_total
        ];

        return response()->json($data,$data["code"]);
    }
}
