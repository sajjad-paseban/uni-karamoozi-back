<?php

require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;

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
            'errors' => $validation->errors()->firstOfAll()
        ];
        return response_json($data, 200);
    }

    $nationalcode = request()->data->national_code;
    $password = request()->data->password;
    
    $res = $db->get('users',[], "nationalcode = $nationalcode");
    $info = $res->fetch_object();

    if($res->num_rows == 1 && $info->status && password_verify($password, $info->password)){
        
        $response = (object)[
            "row" => [
                "user_info" => param_hidden($info, ['password']),
                "token" => "a"
            ],
            "message" => "ورود شما تایید شد.",
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


    return response_json($response, 200);


}