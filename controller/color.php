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

        $res = $db->get('color',[], "id = $id");

        $color_list = $res->fetch_object();

    }else{

        $res = $db->get('color',[]);

        $color_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'color_list' => $color_list,
        ],
        'message' => 'لیست رنگ ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-color"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'color' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'color:required' => customErrorMessage('رنگ', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO color(title, color, status) VALUES(?,?,?)");
    $stmt->bind_param(
        'ssi',
        request()->data->title,
        request()->data->color,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'رنگ با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-color"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'color' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'color:required' => customErrorMessage('رنگ', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE color SET title = ?, color = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'ssii',
        request()->data->title,
        request()->data->color,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'رنگ با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-color"){
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
    $res = $db->query("DELETE FROM color where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'رنگ با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}