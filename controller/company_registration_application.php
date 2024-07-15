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
        
        $res = $db->get('company_registration_application',[],"id = $id");
        $item = $res->fetch_object();

        $item = withForObject($item, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);

        $item->user = param_hidden($item->user, ['password']);

        $company_registration_application_list = $item;

    }else{

        $res = $db->get('company_registration_application',[]);
        $list = $res->fetch_all(MYSQLI_ASSOC);

        $list = withForArray($list, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);

        foreach($list as $key => $row){
            $list[$key]['user'] = param_hidden($row['user'], ['password']);
        }

        $company_registration_application_list = $list;
        
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

if(request()->get->method == "get-data-by-user-id"){
    

    $user_id = request()->data->user_id;
    
    $res = $db->get('company_registration_application',[],"user_id = $user_id");
    
    $item = $res->fetch_object();
    
    if($res->num_rows > 0){
        
        $item = withForObject($item, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);
    
        $item->user = param_hidden($item->user, ['password']);

    }

    $company_registration_application_list = $item;

    

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
    ] , 
    [
        'company_name:required' => customErrorMessage('نام شرکت', 'required'),
        'company_manager_name:required' => customErrorMessage('نام مدیر عامل شرکت', 'required'),
        'company_supervisor_name:required' => customErrorMessage('نام سرپرست شرکت', 'required'),
        'company_supervisor_phone:required' => customErrorMessage('شماره سرپرست شرکت', 'required'),
        'company_telephone:required' => customErrorMessage('شماره تماس شرکت', 'required'),
        'company_address:required' => customErrorMessage('آدرس شرکت', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO company_registration_application(user_id, company_name, company_manager_name, company_supervisor_name, company_supervisor_phone, company_telephone, company_address, description, status) VALUES(?,?,?,?,?,?,?,?, null)");
    $stmt->bind_param(
        'isssiiss',
        request()->data->user_id,
        request()->data->company_name,
        request()->data->company_manager_name,
        request()->data->company_supervisor_name,
        request()->data->company_supervisor_phone,
        request()->data->company_telephone,
        request()->data->company_address,
        request()->data->description
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست ثبت شرکت با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
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

if(request()->get->method == "change-status-company-registration-application"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'cra_id' => "required",
        'status' => "required",
    ] , 
    [
        'cra_id:required' => customErrorMessage('آیدی درخواست', 'required'),
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
    
    $stmt = $db->command()->prepare("UPDATE company_registration_application SET status = ? where id = ?");
    $stmt->bind_param(
        'ii',
        request()->data->status,
        request()->data->cra_id,
    );
    
    $res = $stmt->execute();

    $role_res = null;
    $cra_id = request()->data->cra_id;
    $cra = $db->get('company_registration_application', [], "id = $cra_id")->fetch_object();
    $user_id = $cra->user_id;
    
    if(request()->data->status){   
        $role_res = $db->query("INSERT INTO users_roles(user_id, role_id) VALUES($user_id, 7)");
    }else{
        $role_res = $db->query("DELETE FROM users_roles WHERE user_id = $user_id and role_id = 7");
    }
    
    $response = (object)[
        'message' => ($res) ? 'وضعیت درخواست ثبت شرکت با موفقیت تغییر پیدا کرد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}