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

        $res = $db->get('province',[], "id = $id");

        $province_list = $res->fetch_object();

    }else{

        $res = $db->get('province',[]);

        $province_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'province_list' => $province_list,
        ],
        'message' => 'لیست استان ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-province"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO province(title, status) VALUES(?,?)");
    $stmt->bind_param(
        'si',
        request()->data->title,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'استان با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-province"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE province SET title = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'sii',
        request()->data->title,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'استان با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-province"){
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
    $res = $db->query("DELETE FROM province where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'استان با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}