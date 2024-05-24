<?php

require_once '../helper/function.php';
require_once "../helper/includes.php";

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