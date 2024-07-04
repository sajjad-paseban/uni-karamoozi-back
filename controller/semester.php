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

        $res = $db->get('semester',[], "id = $id");

        $semester_list = $res->fetch_object();

    }else{

        $res = $db->get('semester',[]);

        $semester_list = $res->fetch_all(MYSQLI_ASSOC);
    
    }
    

    
    

    $response = (object)[
        'row' => [
            'semester_list' => $semester_list,
        ],
        'message' => 'لیست نیمسال های تحصیلی با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-semester"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'code' => "required",
        'name' => "required",
        'is_active' => "required",
    ] , 
    [
        'code:required' => customErrorMessage('کد نیمسال تحصیلی', 'required'),
        'name:required' => customErrorMessage('نام نیمسال تحصیلی', 'required'),
        'is_active:required' => customErrorMessage('وضعیت', 'required'),
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

    $db->query('UPDATE semester SET is_active = 0');
    
    $stmt = $db->command()->prepare("INSERT INTO semester(code, name, is_active) VALUES(?,?,?)");
    $stmt->bind_param(
        'isi',
        request()->data->code,
        request()->data->name,
        request()->data->is_active,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'نیمسال تحصیلی با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-semester"){
    $validator = new Validator();

       $validation = $validator->make((array) request()->data, 
    [
        'code' => "required",
        'name' => "required",
        'is_active' => "required",
    ] , 
    [
        'code:required' => customErrorMessage('کد نیمسال تحصیلی', 'required'),
        'name:required' => customErrorMessage('نام نیمسال تحصیلی', 'required'),
        'is_active:required' => customErrorMessage('وضعیت', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE semester SET code = ?, name = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param(
        'isii',
        request()->data->code,
        request()->data->name,
        request()->data->is_active,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'نیمسال تحصیلی با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-semester"){
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
    $res = $db->query("DELETE FROM semester where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'نیمسال تحصیلی با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}