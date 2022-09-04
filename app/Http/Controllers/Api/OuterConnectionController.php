<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OuterConnection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OuterConnectionController extends Controller
{

    public function index(){
        $pagination = 10;
        $page_number = request()->page ?? 1 ;
        $searchText = request()->searchText;
        $outer_connections = DB::select('SELECT * FROM outer_connections LIMIT 10 OFFSET '.( ($page_number -1)*$pagination ));
        $totalOuterConnections = DB::select('SELECT COUNT(*) as total_outer_connections FROM outer_connections');
        $totalOuterConnections = $totalOuterConnections[0]->total_outer_connections;
        $totalPages = ceil(($totalOuterConnections)/$pagination);
    

    
        $response = response($outer_connections,$pagination,$page_number,$totalOuterConnections,$totalPages);

        return response()->json($response,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|max:30',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }
        $outerCon = new OuterConnection();
        $outerCon->client_name = $request->client_name;
        $outerCon->token = (string) Str::uuid();
        $outerCon->save();
        return response()->json([
            'data' => $outerCon
        ], 200);
    }

    public function update($id){
        $outerCon = OuterConnection::findOrFail($id);
        $outerCon->token = (string) Str::uuid();
        $outerCon->save();
        return response()->json([
            'data' => $outerCon
        ], 200);    
    }


}
