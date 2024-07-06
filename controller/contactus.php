<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;


$db = new DB();

if(request()->get->method == "get-data"){
    middleware_user_login(request()->data);
    
    if(isset(request()->data->id)){

        $id = request()->data->id;

        $res = $db->get('contact_us',[], "id = $id");

        $contact_us_list = $res->fetch_object();

    }else{

        $res = $db->get('contact_us',[]);

        $contact_us_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'contact_us_list' => $contact_us_list,
        ],
        'message' => 'لیست درخواست ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-contact-us"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'name' => "required",
        'subject' => "required",
        'email' => "required",
        'description' => "required",
    ] , 
    [
        'name:required' => customErrorMessage('نام و نام خانوادگی', 'required'),
        'subject:required' => customErrorMessage('موضوع', 'required'),
        'email:required' => customErrorMessage('پست الکترونیکی', 'required'),
        'description:required' => customErrorMessage('توضیحات', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO contact_us(name, subject, email, description, status) VALUES(?,?,?,?,null)");
    $stmt->bind_param(
        'ssss',
        request()->data->name,
        request()->data->subject,
        request()->data->email,
        request()->data->description,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست شما ثبت گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "delete-contact-us"){
    middleware_user_login(request()->data);
    
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
    $res = $db->query("DELETE FROM contact_us where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'درخواست با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "change-status"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'id' => "required",
        'status' => "required",
    ] , 
    [
        'id:required' => customErrorMessage('آیدی', 'required'),
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
    
    $stmt = $db->command()->prepare("UPDATE contact_us SET status = ? where id = ?");
    $stmt->bind_param(
        'ii',
        request()->data->status,
        request()->data->id,
    );
    
    $res = $stmt->execute();
    
    $response = (object)[
        'message' => ($res) ? 'وضعیت درخواست جذب کارآموز با موفقیت تغییر پیدا کرد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}