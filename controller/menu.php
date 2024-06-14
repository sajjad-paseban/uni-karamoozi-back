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

        $res = $db->get('menu',[], "id = $id");

        $menu_list = $res->fetch_object();
        
        $menu_list = withForObject($menu_list, [
            "menu" => ['foreign_key' => 'parent_id', 'primary_key' => 'id', 'model_name' => 'parent']
        ]);

    }else{

        $res = $db->get('menu',[]);

        $menu_list = $res->fetch_all(MYSQLI_ASSOC);
    
        $menu_list = withForArray($menu_list, [
            "menu" => ['foreign_key' => 'parent_id', 'primary_key' => 'id', 'model_name' => 'parent']
        ]);
    }
    

    
    

    $response = (object)[
        'row' => [
            'menu_list' => $menu_list,
        ],
        'message' => 'لیست منو ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-menu"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'path' => "required",
        'key_param' => "required",
        'logo' => "required",
        'parent_id' => "required",
        'priority' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'path:required' => customErrorMessage('مسیر', 'required'),
        'key_param:required' => customErrorMessage('کلید', 'required'),
        'logo:required' => customErrorMessage('آیکون', 'required'),
        'parent_id:required' => customErrorMessage('والد', 'required'),
        'priority:required' => customErrorMessage('الویت', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO menu(title,path,key_param,logo,parent_id,priority,status) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param(
        'ssssiii',
        request()->data->title,
        request()->data->path,
        request()->data->key_param,
        request()->data->logo,
        request()->data->parent_id,
        request()->data->priority,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'منو با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-menu"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'path' => "required",
        'key_param' => "required",
        'logo' => "required",
        'parent_id' => "required",
        'priority' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'path:required' => customErrorMessage('مسیر', 'required'),
        'key_param:required' => customErrorMessage('کلید', 'required'),
        'logo:required' => customErrorMessage('آیکون', 'required'),
        'parent_id:required' => customErrorMessage('والد', 'required'),
        'priority:required' => customErrorMessage('الویت', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE menu SET title = ?, path = ?, key_param = ?, logo = ?, parent_id = ?, priority = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'ssssiiii',
        request()->data->title,
        request()->data->path,
        request()->data->key_param,
        request()->data->logo,
        request()->data->parent_id,
        request()->data->priority,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'منو با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-menu"){
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
    $res = $db->query("DELETE FROM menu where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'منو با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}