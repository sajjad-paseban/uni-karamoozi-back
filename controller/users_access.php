<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;

middleware_user_login(request()->data);

$db = new DB();

if(request()->get->method == "get-data"){
    
    if(isset(request()->data->id)){

        $id = request()->data->id;

        $res = $db->get('users_access',[], "id = $id");

        $user_access_list = $res->fetch_object();

    }else{

        $res = $db->get('users_access',[]);

        $user_access_list = $res->fetch_all(MYSQLI_ASSOC);

        $user_access_list = withForArray($user_access_list, [
            "users" => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            "menu" => ['foreign_key' => 'menu_id', 'primary_key' => 'id', 'model_name' => 'menu'],
        ]);
    }
    

    $response = (object)[
        'row' => [
            'user_access_list' => $user_access_list,
        ],
        'message' => 'لیست دسترسی کاربر ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-user-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'userid' => "required",
        'menu_id' => "required",
    ] , 
    [
        'userid:required' => customErrorMessage('کاربر', 'required'),
        'menu_id:required' => customErrorMessage('منو', 'required'),
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

    $user_id = request()->data->userid;
    $menu_id = request()->data->menu_id;
    
    $res = $db->get('users_access', [], "user_id = $user_id and menu_id = $menu_id");
    if($res->num_rows == 0){
        $stmt = $db->command()->prepare("INSERT INTO users_access(user_id, menu_id, status) VALUES(?,?,?)");
        $stmt->bind_param(
            'iii',
            request()->data->userid,
            request()->data->menu_id,
            request()->data->status,
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'دسترسی کاربر با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
            'code' => ($res) ? 200 : 400 
        ];
    }else{
        $response = (object)[
            'message' => 'این دسترسی از قبل اضافه شده است',
            'code' => 400 
        ];
    }

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-user-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'userid' => "required",
        'menu_id' => "required",
    ] , 
    [
        'userid:required' => customErrorMessage('کاربر', 'required'),
        'menu_id:required' => customErrorMessage('منو', 'required'),
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
    
    $user_id = request()->data->userid;
    $menu_id = request()->data->menu_id;
    
    $res = $db->get('users_access', [], "user_id = $user_id and menu_id = $menu_id");
    
    if($res->num_rows == 0 || $res->num_rows == 1){
        
        $stmt = $db->command()->prepare("UPDATE users_access SET user_id = ?, menu_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param(
            'iiii',
            request()->data->userid,
            request()->data->menu_id,
            request()->data->status,
            request()->data->id
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'دسترسی کاربر با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
            'code' => ($res) ? 200 : 400 
        ];
        
    }else{
        $response = (object)[
            'message' => 'این دسترسی از قبل اضافه شده است',
            'code' => 400 
        ];
    }

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-user-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'ids' => "required",
    ] , 
    [
        'ids:required' => customErrorMessage('آیدی', 'required'),
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
    
    $ids = request()->data->ids;
    $res = $db->query("DELETE FROM users_access where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'دسترسی کاربر با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}