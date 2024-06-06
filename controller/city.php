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

        $res = $db->get('city',[], "id = $id");

        $city_list = $res->fetch_object();
        
        $city_list = withForObject($city_list, [
            'province' => ['foreign_key' => 'province_id', 'primary_key' => 'id', 'model_name' => 'province']
        ]);
    }else{

        $res = $db->get('city',[]);

        $city_list = $res->fetch_all(MYSQLI_ASSOC);
        
        $city_list = withForArray($city_list, [
            'province' => ['foreign_key' => 'province_id', 'primary_key' => 'id', 'model_name' => 'province']
        ]);
    }
    

    
    

    $response = (object)[
        'row' => [
            'city_list' => $city_list,
        ],
        'message' => 'لیست شهر ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-city"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'province_id' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'province_id:required' => customErrorMessage('استان', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO city(title ,province_id ,status) VALUES(?,?,?)");
    $stmt->bind_param(
        'sii',
        request()->data->title,
        request()->data->province_id,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'شهر با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-city"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'province_id' => "required",
        'status' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان', 'required'),
        'province_id:required' => customErrorMessage('استان', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE city SET title = ?, province_id = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'siii',
        request()->data->title,
        request()->data->province_id,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'شهر با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-city"){
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
    $res = $db->query("DELETE FROM city where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'شهر با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}