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

        $res = $db->get('company_registration_application',[], "id = $id");

        $company_registration_application_list = $res->fetch_object();

    }else{

        $res = $db->get('company_registration_application',[]);

        $company_registration_application_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'company_registration_application_list' => $company_registration_application_list,
        ],
        'message' => 'لیست درخواست های ثبت شرکت با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-company-registration-application"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'company_name' => "required",
        'company_manager_name' => "required",
        'company_supervisor_name' => "required",
        'company_supervisor_phone' => "required",
        'company_telephone' => "required",
        'company_address' => "required",
        'description' => "required",
        'status' => "required",
    ] , 
    [
        'company_name:required' => customErrorMessage('نام شرکت', 'required'),
        'company_manager_name:required' => customErrorMessage('نام مدیر عامل شرکت', 'required'),
        'company_supervisor_name:required' => customErrorMessage('نام سرپرست شرکت', 'required'),
        'company_supervisor_phone:required' => customErrorMessage('شماره سرپرست شرکت', 'required'),
        'company_telephone:required' => customErrorMessage('شماره تماس شرکت', 'required'),
        'company_address:required' => customErrorMessage('آدرس شرکت', 'required'),
        'description:required' => customErrorMessage('توضیحات', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO company_registration_application(name, code, status) VALUES(?,?,?)");
    $stmt->bind_param(
        'sii',
        request()->data->name,
        request()->data->code,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست ثبت شرکت با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-company-registration-application"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
        [
        'company_name' => "required",
        'company_manager_name' => "required",
        'company_supervisor_name' => "required",
        'company_supervisor_phone' => "required",
        'company_telephone' => "required",
        'company_address' => "required",
        'description' => "required",
        'status' => "required",
    ] , 
    [
        'company_name:required' => customErrorMessage('نام شرکت', 'required'),
        'company_manager_name:required' => customErrorMessage('نام مدیر عامل شرکت', 'required'),
        'company_supervisor_name:required' => customErrorMessage('نام سرپرست شرکت', 'required'),
        'company_supervisor_phone:required' => customErrorMessage('شماره سرپرست شرکت', 'required'),
        'company_telephone:required' => customErrorMessage('شماره تماس شرکت', 'required'),
        'company_address:required' => customErrorMessage('آدرس شرکت', 'required'),
        'description:required' => customErrorMessage('توضیحات', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE company_registration_application SET name = ?, code = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'siii',
        request()->data->name,
        request()->data->code,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست ثبت شرکت با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-company-registration-application"){
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
    $res = $db->query("DELETE FROM company_registration_application where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'درخواست ثبت شرکت با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}