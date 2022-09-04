<?php
use Illuminate\Support\Facades\Http;
use App\Models\User;

function fetchEndpointUserOne(){
    $userEndpoint1  = Http::acceptJson()->get('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1');
    $userEndpoint1Data = json_decode($userEndpoint1->body());
    $dataMissiedCounter = 1;
    foreach($userEndpoint1Data as $user)
    {
    if(isset($user->firstName) && $user->firstName != null && isset($user->lastName) && $user->lastName != null && isset($user->email) && $user->email != null && isset($user->avatar) && $user->avatar != null ){
        User::firstOrCreate(
            ['email' => $user->email],
            [
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'email'=> $user->email,
                'avatar'=> $user->avatar
            ]
        );
        }else{
            $dataMissiedCounter = $dataMissiedCounter + 1;
        }
    }
}

function fetchEndpointUserTwo(){
    $userEndpoint2  = Http::acceptJson()->get('https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/user_2');
    $userEndpoint2Data = json_decode($userEndpoint2->body());
    $dataMissiedCounter = 0;
    foreach($userEndpoint2Data as $user)
    {
        if(isset($user->fName) && $user->fName != null && isset($user->lName) && $user->lName != null && isset($user->email) && $user->email != null && isset($user->picture) && $user->picture != null ){
            User::firstOrCreate(
                ['email' => $user->email],
                [
                    'first_name' => $user->fName,
                    'last_name' => $user->lName,
                    'email'=> $user->email,
                    'avatar'=> $user->picture
                ]
            );
        }else{
            $dataMissiedCounter = $dataMissiedCounter + 1;
        }
    }
}

function responseWithPagination($data,$pagination,$page_number,$total_rows,$totalPages){
    $response =[
        'data' => $data,
        'total_users' => $total_rows,
        'total_pages' => $totalPages,
        'current_page' => $page_number,
    ];
    if($page_number > 1){
        $response['previous_page'] = route('get-all-users').'?page='.$page_number-1;
    }
    if($page_number < $totalPages){
        $response['next_page'] = route('get-all-users').'?page='.$page_number+1;
    }
    return $response;
}