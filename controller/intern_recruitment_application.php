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
        
        $res = $db->get('intern_recruitment_application',[],"id = $id");
        $item = $res->fetch_object();

        $item = withForObject($item, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);

        foreach($item as $key => $row){
            $item[$key]['user'] = param_hidden($row['user'], ['password']);
        }

        $intern_recruitment_application_list = $item;

    }else{

        $res = $db->get('intern_recruitment_application',[]);
        $list = $res->fetch_all(MYSQLI_ASSOC);

        $list = withForArray($list, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);

        foreach($list as $key => $row){
            $list[$key]['user'] = param_hidden($row['user'], ['password']);
        }

        $intern_recruitment_application_list = $list;
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'intern_recruitment_application_list' => $intern_recruitment_application_list,
        ],
        'message' => 'لیست درخواست های جذب کارآموز با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-data-by-user-id"){
    

    $user_id = request()->data->user_id;
    
    $res = $db->get('intern_recruitment_application',[],"user_id = $user_id");
    
    $item = $res->fetch_object();
    
    if($res->num_rows > 0){
        
        $item = withForObject($item, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user']
        ]);
    
        $item->user = param_hidden($item->user, ['password']);

    }

    $intern_recruitment_application_list = $item;

    

    $response = (object)[
        'row' => [
            'intern_recruitment_application_list' => $intern_recruitment_application_list,
        ],
        'message' => 'لیست درخواست های جذب کارآموز با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-intern-recruitment-application"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'code' => "required",
        'group_id' => "required",
        'capacity' => "required",
        'description' => "required",
    ] , 
    [
        'code:required' => customErrorMessage('کد', 'required'),
        'group_id:required' => customErrorMessage('گروه', 'required'),
        'capacity:required' => customErrorMessage('ظرفیت', 'required'),
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
    
    $user_id = request()->data->user_id;
    $cra = $db->get('company_registration_application', [], "user_id = $user_id")->fetch_object();
    $stmt = $db->command()->prepare("INSERT INTO intern_recruitment_application(code, user_id, cra_id, group_id, capacity, description, status) VALUES(?,?,?,?,?,?, null)");
    $stmt->bind_param(
        'siiiis',
        request()->data->code,
        request()->data->user_id,
        $cra->id,
        request()->data->group_id,
        request()->data->capacity,
        request()->data->description
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست جذب کارآموز با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "delete-intern-recruitment-application"){
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
    $res = $db->query("DELETE FROM intern_recruitment_application where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'درخواست جذب کارآموز با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "change-status-intern-recruitment-application"){
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
    
    $stmt = $db->command()->prepare("UPDATE intern_recruitment_application SET status = ? where id = ?");
    $stmt->bind_param(
        'ii',
        request()->data->status,
        request()->data->cra_id,
    );
    
    $res = $stmt->execute();

    $role_res = null;
    $cra_id = request()->data->cra_id;
    $cra = $db->get('intern_recruitment_application', [], "id = $cra_id")->fetch_object();
    $user_id = $cra->user_id;
    
    if(request()->data->status){   
        $role_res = $db->query("INSERT INTO users_roles(user_id, role_id) VALUES($user_id, 7)");
    }else{
        $role_res = $db->query("DELETE FROM users_roles WHERE user_id = $user_id and role_id = 7");
    }
    
    $response = (object)[
        'message' => ($res) ? 'وضعیت درخواست جذب کارآموز با موفقیت تغییر پیدا کرد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}