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

        $res = $db->get('users_roles',[], "id = $id");

        $users_roles_list = $res->fetch_object();

    }else{

        $res = $db->get('users_roles',[]);

        $users_roles_list = $res->fetch_all(MYSQLI_ASSOC);
        
        $users_roles_list = withForArray($users_roles_list, 
        [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'roles' => ['foreign_key' => 'role_id', 'primary_key' => 'id', 'model_name' => 'role'],
        ]
        );
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'users_roles_list' => $users_roles_list,
        ],
        'message' => 'لیست نقش و کاربر ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-users-roles"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'role_id' => "required",
        'status' => "required",
    ] , 
    [
        'user_id:required' => customErrorMessage('کابر', 'required'),
        'role_id:required' => customErrorMessage('نقش', 'required'),
        'status:required' => customErrorMessage('وضعیت', 'required'),
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
    $role_id = request()->data->role_id;

    $count = $db->get("users_roles", [], "user_id = $user_id and role_id = $role_id")->num_rows;
    if($count == 0){
        $stmt = $db->command()->prepare("INSERT INTO users_roles(user_id, role_id, status) VALUES(?,?,?)");
        $stmt->bind_param(
            'iii',
            $user_id,
            $role_id,
            request()->data->status,
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'نقش و کاربر با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
            'code' => ($res) ? 200 : 400 
        ];
    }else{
        $response = (object)[
            'message' => 'نقش از قبل برای شما ثبت شده است',
            'code' => 400 
        ];
    }

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-users-roles"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'role_id' => "required",
        'status' => "required",
    ] , 
    [
        'user_id:required' => customErrorMessage('کابر', 'required'),
        'role_id:required' => customErrorMessage('نقش', 'required'),
        'status:required' => customErrorMessage('وضعیت', 'required'),
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
    $role_id = request()->data->role_id;

    $count = $db->get("users_roles", [], "user_id = $user_id and role_id = $role_id")->num_rows;
    if($count == 0){
        $stmt = $db->command()->prepare("UPDATE users_roles SET user_id = ?, role_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param(
            'iiii',
            $user_id,
            $role_id,
            request()->data->status,
            request()->data->id
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'نقش و کاربر با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
            'code' => ($res) ? 200 : 400 
        ];
    }else{
        $response = (object)[
            'message' => 'نقش از قبل برای شما ثبت شده است',
            'code' => 400 
        ];       
    }


    return response_json($response, $response->code);
}

if(request()->get->method == "delete-users-roles"){
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
    $res = $db->query("DELETE FROM users_roles where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'نقش و کاربر با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-user-roles"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
    ] , 
    [
        'user_id:required' => customErrorMessage('کابر', 'required'),
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

    $res = $db->get("users_roles", [], "user_id = $user_id");
    if($res->num_rows > 0){
        $data = withForArray($res->fetch_all(MYSQLI_ASSOC), [
            "users" => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user', 'hidden' => ['password']],
            "roles" => ['foreign_key' => 'role_id', 'primary_key' => 'id', 'model_name' => 'role']
        ]);
        
        $response = (object)[
            'row' => $data,
            'message' => 'نقش ها ارسال گردید',
            'code' => 200 
        ];
    }else{
        $response = (object)[
            'message' => 'نقشی برای شما ثبت نشده است',
            'code' => 400 
        ];       
    }


    return response_json($response, $response->code);
}

if(request()->get->method == "update-default-user-role"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'role_id' => "required",
    ] , 
    [
        'user_id:required' => customErrorMessage('کابر', 'required'),
        'role_id:required' => customErrorMessage('نقش', 'required'),
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
    $role_id = request()->data->role_id;
    
    $delete_res = $db->query("UPDATE users_roles SET default_role = 0 WHERE user_id = $user_id");
    $add_res = $db->query("UPDATE users_roles SET default_role = 1 WHERE user_id = $user_id and role_id = $role_id");

    if($delete_res && $add_res){
        
        $response = (object) [
            "message" => 'نقش پیشفرض بروز رسانی گردید',
            'code' => 200
        ];

    }else{
        
        $response = (object) [
            "message" => 'عملیات با خطا مواجه گردید',
            'code' => 400
        ];
        
    }

    return response_json($response, $response->code);
}