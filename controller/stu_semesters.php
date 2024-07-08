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

        $res = $db->get('stu_semesters',[], "id = $id");

        $stu_semesters_list = $res->fetch_object();

        if($res->num_rows > 0){    
            $stu_semesters_list = withForObject($stu_semesters_list, [
                'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
                'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
                'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group']
            ]);

            $stu_semesters_list->user = param_hidden($stu_semesters_list->user, ['password']);
            $stu_semesters_list->user->fullname = $stu_semesters_list->user->fname .' '.$stu_semesters_list->user->lname;
        }

    }else{

        $res = $db->get('stu_semesters',[]);
        $stu_semesters_list = $res->fetch_all(MYSQLI_ASSOC);
        
        if($res->num_rows > 0){    
            $stu_semesters_list = withForArray($stu_semesters_list, [
                'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
                'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
                'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group']
            ]);

            foreach($stu_semesters_list as $key => $row){
                $stu_semesters_list[$key]['user'] = param_hidden($row['user'], ['password']);
                $stu_semesters_list[$key]['user']->fullname = $row['user']->fname .' '.$row['user']->lname;
            }
        }
        
    }

    $response = (object)[
        'row' => [
            'stu_semesters_list' => $stu_semesters_list,
        ],
        'message' => 'لیست دانشجو ها و نیمسال های تحصیلی با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-users-by-role"){
    
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'role_id' => "required",
    ] , 
    [
        'role_id:required' => customErrorMessage('نقش', 'required'),
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

    
    $role = request()->data->role_id;
    
    $res = $db->query(
        "SELECT * FROM users WHERE id in(SELECT user_id FROM users_roles WHERE role_id = $role)"
    )->fetch_all(MYSQLI_ASSOC);
    
    foreach($res as $key => $row){
        $res[$key] = param_hidden($row, ['password']);
    }
    

    $response = (object)[
        'row' => [
            'users' => $res,
        ],
        'message' => 'لیست کاربران با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-stu-semesters"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'semester_id' => "required",
        'group_id' => "required",
        'userid' => "required",
        'status' => "required",
    ] , 
    [
        'semester_id:required' => customErrorMessage('نیمسال تحصیلی', 'required'),
        'group_id:required' => customErrorMessage('نام گروه', 'required'),
        'userid:required' => customErrorMessage('نام کاربر', 'required'),
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

    $uq = $db->get(
        'stu_semesters',
         [],
        "semester_id = ". request()->data->semester_id . " and user_id = ".request()->data->userid
    );
    
    if($uq->num_rows == 0){
        $stmt = $db->command()->prepare("INSERT INTO stu_semesters(semester_id, user_id, group_id, status) VALUES(?,?,?,?)");
        $stmt->bind_param(
            'iiii',
            request()->data->semester_id,
            request()->data->userid,
            request()->data->group_id,
            request()->data->status,
        );
        
        $res = $stmt->execute();
    }else{
        $res = false;
    }

    $response = (object)[
        'message' => ($res) ? 'دانشجو و نیمسال تحصیلی با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-stu-semesters"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'semester_id' => "required",
        'userid' => "required",
        'group_id' => "required",
        'status' => "required",
    ] , 
    [
        'semester_id:required' => customErrorMessage('نیمسال تحصیلی', 'required'),
        'userid:required' => customErrorMessage('نام کاربر', 'required'),
        'group_id:required' => customErrorMessage('نام گروه', 'required'),
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

    $uq = $db->get(
        'stu_semesters',
         [],
        "semester_id = ". request()->data->semester_id . " and user_id = ".request()->data->userid
    );
    if($uq->num_rows == 0 || $uq->num_rows == 1){
        
        $stmt = $db->command()->prepare("UPDATE stu_semesters SET semester_id = ?, user_id = ?, group_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param(
            'iiiii',
            request()->data->semester_id,
            request()->data->userid,
            request()->data->group_id,
            request()->data->status,
            request()->data->id
        );
        
        $res = $stmt->execute();
    
    }else{
        $res = false;
    }

    $response = (object)[
        'message' => ($res) ? 'دانشجو و نیمسال تحصیلی با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-stu-semesters"){
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
    $res = $db->query("DELETE FROM stu_semesters where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'دانشجو و نیمسال تحصیلی با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}