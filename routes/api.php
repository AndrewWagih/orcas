<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use \App\Http\Controllers\Api\OuterConnectionController as OuterConnectionController;
use \App\Http\Controllers\Api\HomeController as HomeController;
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

Route::get('/user', function (Request $request) {
    test();
});

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


Route::get('/tokens/create', function () {
    $token = \App\Models\User::first()->createToken('login');
    return ['token' => $token->plainTextToken];
});

Route::get('test-fetch',function(){
    $userEndpoint1  = Http::acceptJson()->get('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1');
    $userEndpoint1Data = json_decode($userEndpoint1->body());
    // return $userEndpoint1Data;
    $validator = Validator::make($userEndpoint1Data, [
        '*.email'   => ['required','email'],
        '*.firstName' => 'required',
        '*.lasttName' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['error'=>'Some Values is Missed please Check End Point'], 422);
    }
    foreach($userEndpoint1Data as $user)
    {
        return $user;
        \App\Models\User::firstOrCreate(
            
            [
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'email'=> $user->email,
                'avatar'=> $user->avatar
            ]
        );
    }
});
