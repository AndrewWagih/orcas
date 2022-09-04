<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use \App\Http\Controllers\Api\OuterConnectionController as OuterConnectionController;
use \App\Http\Controllers\Api\HomeController as HomeController;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::get('get-all-users',[HomeController::class,'allUsers'])->name('get-all-users');
    
    Route::get('users-search',function(){
        
    })->name('users-search');

    Route::get('outer-connections',[OuterConnectionController::class,'index']);
    Route::post('outer-connections',[OuterConnectionController::class,'store']);
    Route::post('outer-connections/{id}',[OuterConnectionController::class,'update']);
});

Route::group(['middleware' => 'OuterConnection'], function (){
    Route::get('users',[HomeController::class,'allUsers'])->name('get-all-users.outer');
    
});


Route::post('/auth', function () {

    // return request()->all();
    $validator = Validator::make(request()->all(), [
        'email'   => 'required|email',
        'password' => 'required|min:6',

    ]);
    if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()], 401);
    }

    if (Auth::guard('admin')->attempt(['email' => request()->email, 'password' => request()->password])) {
        $token = Auth::guard('admin')->user()->createToken('Admin');
        return ['token' => $token->plainTextToken];
    }else{
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    // $token = \App\Models\User::first()->createToken('login');
});


