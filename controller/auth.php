<?php

require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;


$db = new DB();

if(request()->get->method == "register"){
    $data = request()->post;
    
    $num_rows = $db->get('users', [], "nationalcode = '".$data->national_code. "' or email = '".$data->email."'")->num_rows;
    if($num_rows == 0){
        $stmt = $db->command()->prepare('insert into users(nationalcode, phone, email, password) values(?,?,?,?)');
        $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt->bind_param(
            'isss', 
            $data->national_code,
            $data->phone,
            $data->email,
            $hashed_password
        );
    
        $stmt->execute();
        
        $response = (object) [
            'user_id' => $stmt->insert_id,
            'message' => "عملیات با موفقیت انجام شد",
            'code' => 201
        ];
    }else{
        $response = (object) [
            'user_id' => null,
            'message' => "کاربری با این مشخصات از قبل ثبت نام کرده است",
            'code' => 400
        ];
    }
    
    

    return response_json($response, $response->code);
}

if(request()->get->method == "login"){
    $validator = new Validator();

    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'national_code'  => 'required',
        'password'       => 'required',
    ]);

    $validation->setMessages([
        "national_code:required" => 'فیلد کد ملی اجباری می باشد',
        "password:required" => 'فیلد گذرواژه اجباری می باشد'
    ]);


    $validation->validate();
    
    if($validation->fails()){
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];
        return response_json($data, $data->code);
    }

    $nationalcode = request()->data->national_code;
    $password = request()->data->password;
    
    $res = $db->get('users',[], "nationalcode = $nationalcode");
    $info = $res->fetch_object();

    if($res->num_rows == 1 && $info->status && password_verify($password, $info->password)){
        
        $auth_token = $db->get('auth_token',[], "user_id = $info->id and type = 1 and status = 1")->fetch_object();
        if($auth_token){
            $response = (object)[
                "message" => "کاربر session فعال دارد",
                "code" => 400
            ];

            return response_json($response, $response->code);
        }

        $token = bin2hex($info->id.random_bytes(16).'token');
        $type = 1;
        $status = true;
        $date = Carbon::now()->addDays(2);        
        
        $stmt = $db->command()->prepare("insert into auth_token(user_id, token, type, status, expire_date) values(?,?,?,?,?)");
        $stmt->bind_param(
            "isiis",
            $info->id,
            $token,
            $type,
            $status,
            $date
        );
        
        $stmt->execute();

        $response = (object)[
            "row" => [
                "user_info" => param_hidden($info, ['password']),
                "token" => $token
            ],
            "message" => "درحال انتقال به پنل کاربری می باشید",
            "code" => 200
        ];
    }
    else{
        $response = (object)[
            "row" => null,
            "message" => "کد ملی یا گذرواژه اشتباه می باشد.",
            "code" => 401
        ];
    }


    return response_json($response, $response->code);


}

if(request()->get->method == "logout"){
    $validator = new Validator();

    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'user_id'  => 'required',
        'token'       => 'required',
    ]);

    $validation->setMessages([
        "user_id:required" => 'آیدی کاربر می بایست ارسال شود',
        "token:required" => 'token می بایست ارسال شود'
    ]);


    $validation->validate();
    
    if($validation->fails()){
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $user_id = request()->data->user_id;
    $token = request()->data->token;
    
    
    $res = $db->get('auth_token',[], "user_id = $user_id and token = '$token' and type = 1 and status = 1");
    $info = $res->fetch_object();

    if($res->num_rows == 1){
        
        $db->query("UPDATE auth_token SET status = 0 where user_id = $user_id and token = '$token'");

        $response = (object)[
            "message" => "خروج از سامانه با موفقیت انجام شد",
            "code" => 200
        ];
    }
    else{
        $response = (object)[
            "row" => null,
            "message" => "همچین session ای وجود ندارد",
            "code" => 401
        ];
    }


    return response_json($response, $response->code);


}