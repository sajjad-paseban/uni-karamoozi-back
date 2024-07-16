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

        $res = $db->get('uni_group',[], "id = $id");

        $uni_group_list = $res->fetch_object();

    }else{

        $res = $db->get('uni_group',[]);

        $uni_group_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'uni_group_list' => $uni_group_list,
        ],
        'message' => 'لیست گروه ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-uni-group"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'name' => "required",
        'code' => "required",
        'status' => "required",
    ] , 
    [
        'name:required' => customErrorMessage('عنوان گروه', 'required'),
        'code:required' => customErrorMessage('کد گروه', 'required'),
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

    $stmt = $db->command()->prepare("INSERT INTO uni_group(name, code, status) VALUES(?,?,?)");
    $stmt->bind_param(
        'sii',
        request()->data->name,
        request()->data->code,
        request()->data->status,
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'گروه با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-uni-group"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'name' => "required",
        'code' => "required",
        'status' => "required",
    ] , 
    [
        'name:required' => customErrorMessage('عنوان گروه', 'required'),
        'code:required' => customErrorMessage('کد گروه', 'required'),
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

    $stmt = $db->command()->prepare("UPDATE uni_group SET name = ?, code = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'siii',
        request()->data->name,
        request()->data->code,
        request()->data->status,
        request()->data->id
    );
    
    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'گروه با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-uni-group"){
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
    
    $db->query("DELETE FROM users_groups WHERE group_id IN($ids)");
    $db->query("DELETE FROM intern_recruitment_application WHERE group_id IN($ids)");
    $db->query("DELETE FROM stu_request WHERE group_id IN($ids)");
    $db->query("DELETE FROM stu_semesters WHERE group_id IN($ids)");
    $res = $db->query("DELETE FROM uni_group where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'گروه با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}