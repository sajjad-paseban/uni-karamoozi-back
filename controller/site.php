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

        $res = $db->get('sites_management',[], "id = $id");

        $site_list = $res->fetch_object();

    }else{

        $res = $db->get('sites_management',[]);

        $site_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'site_list' => $site_list,
        ],
        'message' => 'لیست سایت ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-site"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'name' => "required",
        'link' => "required",
        'status' => "required",
    ] , 
    [
        'name:required' => customErrorMessage('نام سایت', 'required'),
        'link:required' => customErrorMessage('لینک سایت', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO sites_management(name, link, status) VALUES(?,?,?)");
    $stmt->bind_param(
        'ssi',
        request()->data->name,
        request()->data->link,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'سایت با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-site"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'name' => "required",
        'link' => "required",
        'status' => "required",
    ] , 
    [
        'name:required' => customErrorMessage('نام سایت', 'required'),
        'link:required' => customErrorMessage('لینک سایت', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE sites_management SET name = ?, link = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'ssii',
        request()->data->name,
        request()->data->link,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'سایت با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-site"){
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
    $res = $db->query("DELETE FROM sites_management where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'سایت با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}