<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Low;
use Illuminate\Support\Facades\DB;

class LowController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show','totalLowDate','totalLowDay','totalLowMonth','totalLowYear']]);
    }

    public function index(){
        $lows = Low::all();

        $data = [
            "status" => "success",
            "code"   => 200,
            "lows"   => $lows
        ];

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $low = Low::find($id);

        if(!empty($low) || is_object($low)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "low"   => $low
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "No se encuentra el pago de la baja de fierro"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        $json = $request->input('json');
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'invoice' => 'required|unique:low',
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
            $low = new Low();
            $low->invoice = $params_array['invoice'];
            $low->amount  = $params_array['amount'];

            $low->save();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'low'   => $low
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function totalLowDate($date){
        $low_total = DB::table('low')
                        ->whereDate('created_at',$date)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "low_total" => $low_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalLowDay($day){
        $low_total = DB::table('low')
                        ->whereDay('created_at',$day)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "low_total" => $low_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalLowMonth($month){
        $low_total = DB::table('low')
                        ->whereMonth('created_at',$month)
                        ->get();

        $data = [
            "status"     => "success",
            "code"       => 200,
            "low_total" => $low_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalLowYear($year){
        $low_total = DB::table('low')
                        ->whereYear('created_at',$year)
                        ->get();

        if(!empty($low_total) || is_object($low_total)){
            $data = [
                "status"     => "success",
                "code"       => 200,
                "low_total" => $low_total
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "error al encontrar el año de la baja"
            ];
        }

        return response()->json($data,$data["code"]);
    }

}
