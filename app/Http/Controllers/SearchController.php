<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Search;

class SearchController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show','totalSearchDate','totalSearchDay','totalSearchMonth','totalSearchYear']]);
    }

    public function index(){
        $searchs = Search::all();

        $data = [
            "status" => "success",
            "code"   => 200,
            "serachs" => $searchs
        ];

        return response()->json($data,$data["code"]);
    }

    public function show($id){
        $search = Search::find($id);

        if(!empty($search) || is_object($search)){
            $data = [
                "status" => "success",
                "code"   => 200,
                "serach" => $search
            ];
        }else{
            $data = [
                "status"  => "error",
                "code"    => 404,
                "message" => "error al buscar la busqueda de pago"
            ];
        }

        return response()->json($data,$data["code"]);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params_array = json_decode($json,true);

        $validator = \Validator::make($params_array,[
            'invoice' => 'required|unique:search',
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
            $search = new Search();
            $search->invoice = $params_array['invoice'];
            $search->amount  = $params_array['amount'];

            $search->save();

            $data = [
                'status' => 'success',
                'code'   => 200,
                'search' => $search
            ];
        }

        return response()->json($data,$data['code']);
    }

    public function totalSearchDate($date){
        $search_total = DB::table('search')
                        ->whereDate('created_at',$date)
                        ->get();

        $data = [
            "status"       => "success",
            "code"         => 200,
            "search_total" => $search_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalSearchDay($day){
        $search_total = DB::table('search')
                        ->whereDay('created_at',$day)
                        ->get();

        $data = [
            "status"       => "success",
            "code"         => 200,
            "search_total" => $search_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalSearchMonth($month){
        $search_total = DB::table('search')
                        ->whereMonth('created_at',$month)
                        ->get();

        $data = [
            "status"       => "success",
            "code"         => 200,
            "search_total" => $search_total
        ];

        return response()->json($data,$data["code"]);
    }

    public function totalSearchYear($year){
        $search_total = DB::table('search')
                        ->whereYear('created_at',$year)
                        ->get();

        $data = [
            "status"       => "success",
            "code"         => 200,
            "search_total" => $search_total
        ];

        return response()->json($data,$data["code"]);
    }

}
