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

        $res = $db->get('users_groups',[], "id = $id");

        $users_groups_list = $res->fetch_object();

        if($res->num_rows > 0){    
            $users_groups_list = withForObject($users_groups_list, [
                'roles' => ['foreign_key' => 'role_id', 'primary_key' => 'id', 'model_name' => 'role'],
                'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
                'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group']
            ]);

            $users_groups_list->user = param_hidden($users_groups_list->user, ['password']);
            $users_groups_list->user->fullname = $users_groups_list->user->fname .' '.$users_groups_list->user->lname;
        }

    }else{

        $res = $db->get('users_groups',[]);
        $users_groups_list = $res->fetch_all(MYSQLI_ASSOC);
        
        if($res->num_rows > 0){    
            $users_groups_list = withForArray($users_groups_list, [
                'roles' => ['foreign_key' => 'role_id', 'primary_key' => 'id', 'model_name' => 'role'],
                'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
                'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group']
            ]);

            foreach($users_groups_list as $key => $row){
                $users_groups_list[$key]['user'] = param_hidden($row['user'], ['password']);
                $users_groups_list[$key]['user']->fullname = $row['user']->fname .' '.$row['user']->lname;
            }
        }
        
    }

    $response = (object)[
        'row' => [
            'users_groups_list' => $users_groups_list,
        ],
        'message' => 'لیست کاربر ها و گروه ها با موفقیت ارسال شذ',
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

if(request()->get->method == "create-user-group"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'role_id' => "required",
        'userid' => "required",
        'group_id' => "required",
        'status' => "required",
    ] , 
    [
        'role_id:required' => customErrorMessage('نقش', 'required'),
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
        'users_groups',
         [],
        "role_id = ". request()->data->role_id . " and user_id = ".request()->data->userid. ' and group_id = '. request()->data->group_id
    );
    if($uq->num_rows == 0){
        $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
        $stmt->bind_param(
            'iiii',
            request()->data->role_id,
            request()->data->userid,
            request()->data->group_id,
            request()->data->status,
        );
        
        $res = $stmt->execute();
    }else{
        $res = false;
    }

    $response = (object)[
        'message' => ($res) ? 'کاربر و گروه با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-user-group"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'role_id' => "required",
        'userid' => "required",
        'group_id' => "required",
        'status' => "required",
    ] , 
    [
        'role_id:required' => customErrorMessage('نقش', 'required'),
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
        'users_groups',
         [],
        "role_id = ". request()->data->role_id . " and user_id = ".request()->data->userid. ' and group_id = '. request()->data->group_id
    );
    if($uq->num_rows == 0){
        
        $stmt = $db->command()->prepare("UPDATE users_groups SET role_id = ?, user_id = ?, group_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param(
            'iiiii',
            request()->data->role_id,
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
        'message' => ($res) ? 'کاربر و گروه با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-user-group"){
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
    $res = $db->query("DELETE FROM users_groups where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'کاربر و گروه با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}