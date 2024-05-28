<?php

include '../helper/includes.php';

use Carbon\Carbon;
function middleware_user_login($auth){
    $db = new DB();    
    
    if(isset($auth->user_id) && isset($auth->token)){
        $user_id = $auth->user_id;
        $token = $auth->token;
        
        $res = $db->get('auth_token', [], "user_id = $user_id and token = '$token' and type = 1 and status = 1");
        if($res->num_rows == 1){
            $auth_token = $res->fetch_object();
            $diff = Carbon::now()->diffInDays(Carbon::parse($auth_token->expire_date)); 
            
            if($diff < 0){
                $db->query("UPDATE auth_token SET status = 0 WHERE user_id = $user_id and token = $token");
            }
            
        }else{
            $response = (object)[
                'message' => 'خطای authentication',
                'code' => 401
            ];

            die(response_json($response, $response->code));
        }
    }
    
}