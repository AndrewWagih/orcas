<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function allUsers(){
        $pagination = 10;
        $page_number = request()->page ?? 1 ;
        $users = DB::select('SELECT * FROM users LIMIT 10 OFFSET '.( ($page_number -1)*$pagination ));
        $totalUsers  = \App\Models\User::count();
        $totalPages = ceil(($totalUsers)/$pagination);
        $response = responseWithPagination($users,$pagination,$page_number,$totalUsers,$totalPages);

        return response()->json($response,200);
    }

    public function userSearch(){

        $pagination = 10;
        $page_number = request()->page ?? 1 ;
        $searchText = request()->searchText;
        $users = DB::select('SELECT * FROM users WHERE first_name LIKE \'%'.$searchText.'%\' OR last_name LIKE \'%'.$searchText.'%\' OR email LIKE \'%'.$searchText.'%\' LIMIT 10 OFFSET '.( ($page_number -1)*$pagination ));
        $totalUsers = DB::select('SELECT COUNT(*) as totalUsers FROM users WHERE first_name LIKE \'%'.$searchText.'%\' OR last_name LIKE \'%'.$searchText.'%\' OR email LIKE \'%'.$searchText.'%\'');
        $totalUsers = $totalUsers[0]->totalUsers;
        $totalPages = ceil(($totalUsers)/$pagination);

        $response = responseWithPagination($users,$pagination,$page_number,$totalUsers,$totalPages);

        return response()->json($response,200);
    }

}
