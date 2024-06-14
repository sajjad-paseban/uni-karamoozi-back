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

        $res = $db->get('roles_access',[], "id = $id");

        $role_access_list = $res->fetch_object();

    }else{

        $res = $db->get('roles_access',[]);

        $role_access_list = $res->fetch_all(MYSQLI_ASSOC);

        $role_access_list = withForArray($role_access_list, [
            "roles" => ['foreign_key' => 'role_id', 'primary_key' => 'id', 'model_name' => 'role'],
            "menu" => ['foreign_key' => 'menu_id', 'primary_key' => 'id', 'model_name' => 'menu'],
        ]);
    }
    

    $response = (object)[
        'row' => [
            'role_access_list' => $role_access_list,
        ],
        'message' => 'لیست دسترسی نقش ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-role-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'role_id' => "required",
        'menu_id' => "required",
    ] , 
    [
        'role_id:required' => customErrorMessage('نقش', 'required'),
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

    $role_id = request()->data->role_id;
    $menu_id = request()->data->menu_id;
    
    $res = $db->get('roles_access', [], "role_id = $role_id and menu_id = $menu_id");
    if($res->num_rows == 0){
        $stmt = $db->command()->prepare("INSERT INTO roles_access(role_id, menu_id, status) VALUES(?,?,?)");
        $stmt->bind_param(
            'iii',
            request()->data->role_id,
            request()->data->menu_id,
            request()->data->status,
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'دسترسی نقش با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
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

if(request()->get->method == "update-role-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'role_id' => "required",
        'menu_id' => "required",
    ] , 
    [
        'role_id:required' => customErrorMessage('نقش', 'required'),
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
    
    $role_id = request()->data->role_id;
    $menu_id = request()->data->menu_id;
    
    $res = $db->get('roles_access', [], "role_id = $role_id and menu_id = $menu_id");
    
    if($res->num_rows == 0 || $res->num_rows == 1){
        
        $stmt = $db->command()->prepare("UPDATE roles_access SET role_id = ?, menu_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param(
            'iiii',
            request()->data->role_id,
            request()->data->menu_id,
            request()->data->status,
            request()->data->id
        );
        
        $res = $stmt->execute();
    
        $response = (object)[
            'message' => ($res) ? 'دسترسی نقش با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
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

if(request()->get->method == "delete-role-access"){
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
    $res = $db->query("DELETE FROM roles_access where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'دسترسی نقش با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}