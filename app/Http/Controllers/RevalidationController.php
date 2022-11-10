<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Revalidation;
use Illuminate\Support\Facades\DB;

class RevalidationController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show','totalRevalidationDate','totalRevalidationDay','totalRevalidationMonth','totalRevalidationYear']]);
    }

    public function index(){
        $revalidations = Revalidation::all();

        $data = [
            "status"        => "success",
            "code"          => 200,
            "revalidations" => $revalidations
        ];

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $revalidation = Revalidation::find($id);

        if(!empty($revalidation) || is_object($revalidation)){
            $data = [
                "status"        => "success",
                "code"          => 200,
                "revalidation"  => $revalidation
            ];
        }else{
            $data = [
                "status"   => "error",
                "code"     => 404,
                "message"  => "No se encuentra el pago de la revalidacion"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'invoice' => 'required|unique:revalidation',
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
            $revalidation = new Revalidation();
            $revalidation->invoice = $params_array['invoice'];
            $revalidation->amount  = $params_array['amount'];

            $revalidation->save();

            $data = [
                'status'       => 'success',
                'code'         => 200,
                'revalidation' => $revalidation
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function totalRevalidationDate($date){
        $revalidation_total = DB::table('revalidation')
                        ->whereDate('created_at',$date)
                        ->get();

        $data = [
            "status"             => "success",
            "code"               => 200,
            "revalidation_total" => $revalidation_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalRevalidationDay($day){
        $revalidation_total = DB::table('revalidation')
                        ->whereDay('created_at',$day)
                        ->get();

        $data = [
            "status"             => "success",
            "code"               => 200,
            "revalidation_total" => $revalidation_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalRevalidationMonth($month){
        $revalidation_total = DB::table('revalidation')
                        ->whereMonth('created_at',$month)
                        ->get();

        $data = [
            "status"             => "success",
            "code"               => 200,
            "revalidation_total" => $revalidation_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalRevalidationYear($year){
        $revalidation_total = DB::table('revalidation')
                        ->whereYear('created_at',$year)
                        ->get();

        $data = [
            "status"             => "success",
            "code"               => 200,
            "revalidation_total" => $revalidation_total
        ];

        return response()->json($data,$data["code"]);
    }
}
