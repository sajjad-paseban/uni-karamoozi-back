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

        $res = $db->get('users',[], "id = $id");

        $user_list = $res->fetch_object();

    }else{

        $res = $db->get('users',[]);

        $user_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }

    $response = (object)[
        'row' => [
            'user_list' => $user_list,
        ],
        'message' => 'لیست کاربر ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-info"){
    
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'token' => "required"
    ] , 
    [
        'user_id:required' => 'آیدی کاربر اجباری می باشد',
        'token:required' => 'فیلد token اجباری می باشد'
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
    $user = $db->get('users', [], "id = $user_id");
    $info = $user->fetch_object();
    $info->fullname = strlen(($info->fname .''. $info->lname)) > 0 ? ($info->fname .' '. $info->lname) : null;
    $response = (object)[
        "row" => [
            "info" => param_hidden($info, ['password']),
        ],
        "message" => "اطلاعات دریافت شد",
        "code" => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "update-user-info"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'fname' => "required",
        'lname' => "required",
        'birthdate' => "required",
        'nationalcode' => "required|min:10|max:10",
        'phone' => "required|min:11|max:11",
        'email' => "required|email",
    ] , 
    [
        'fname:required' => "فیلد نام اجباری می باشد",
        'lname:required' => "فیلد نام خانوادگی اجباری می باشد",
        'birthdate:required' => "فیلد تاریخ اجباری می باشد",
        'nationalcode:required' => "فیلد کد ملی اجباری می باشد",
        'nationalcode:min' => "فیلد کد ملی می بایست 10 رقمی باشد",
        'nationalcode:max' => "فیلد کد ملی می بایست 10 رقمی باشد",
        'phone:required' => "فیلد شماره همراه اجباری می باشد",
        'phone:min' => "فیلد شماره همراه می بایست 11 رقمی باشد",
        'phone:max' => "فیلد شماره همراه می بایست 11 رقمی باشد",
        'email:required' => "فیلد پست الکترونیکی اجباری می باشد",
        'email:email' => "پست الکترونیکی اجباری می باشد",
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

    $fname = request()->data->fname;
    $lname = request()->data->lname;
    $birthdate = request()->data->birthdate;
    $phone = request()->data->phone;

    $res = $db->query(
        "UPDATE users SET
        fname = '$fname',
        lname = '$lname',
        birthdate = '$birthdate',
        phone = '$phone' WHERE id = $user_id"
    );

    if($res)
        $response = (object)[
            'message' => 'مشخصات شما بروز رسانی گردید',
            'code' => 200
        ];
    else
        $response = (object)[
            'message' => 'عملیات با خظا مواچه شد',
            'code' => 400
        ];

    return response_json($response, $response->code);

}

if(request()->get->method == "profile-picture-upload"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'image' => "required"
    ] , 
    [
        'image:required' => 'تصویر پروفایل اجباری می باشد'
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
    
    $file = $_FILES['image'];
    $filename = 'user-' . request()->data->user_id . '-profile-pic.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $user_id = request()->data->user_id;
    $path =  '../storage/user/'.$filename;
    $db_path =  'storage/user/'.$filename;
    
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $path)){
        $db->query("UPDATE users SET image_path = '$db_path' WHERE id = $user_id");
        
        $response = (object) [
            'path' => $path,
            'message' => 'تصویر پروفایل شما ذخیره شد',
            'code' => 200
        ];
    }
    else{
        $response = (object) [
            'message' => 'عملیات با خطا مواجه شد',
            'code' => 400
        ];
    }
        
    return response_json($response, $response->code);
}

if(request()->get->method == "create-user"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'nationalcode' => "required",
        'phone' => "required",
        'email' => "required|email",
        'password' => "required",
        'status' => "required",
    ] , 
    [
        'nationalcode:required' => customErrorMessage('کد ملی', 'required'),
        'phone:required' => customErrorMessage('شماره همراه', 'required'),
        'email:required' => customErrorMessage('پست الکترونیکی', 'required'),
        'email:email' => customErrorMessage('پست الکترونیکی', 'email'),
        'password:required' => customErrorMessage('گذرواژه', 'required'),
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

    $data = request()->data;
    
    $num_rows = $db->get('users', [], "nationalcode = '".$data->nationalcode. "' or email = '".$data->email."'")->num_rows;
    if($num_rows == 0){
        $stmt = $db->command()->prepare('insert into users(nationalcode, phone, email, password, status) values(?,?,?,?,?)');
        $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt->bind_param(
            'isssi', 
            $data->nationalcode,
            $data->phone,
            $data->email,
            $hashed_password,
            $data->status,
        );
    
        $stmt->execute();
 
        $us_id = $stmt->insert_id;
        $ro_id = env('DEFAULT_ROLE');
        $active = true;
        $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");
        $stmt->bind_param(
            'iii', 
            $ro_id,
            $us_id,
            $active
        );  
        $stmt->execute();
        
        $response = (object) [
            'user_id' => $us_id,
            'message' => "عملیات با موفقیت انجام شد",
            'code' => 201
        ];
    }else{
        $response = (object) [
            'user_id' => null,
            'message' => "کاربری با این مشخصات از قبل ثبت نام کرده است",
            'code' => 400
        ];
    }
    
    

    return response_json($response, $response->code);
    
}

if(request()->get->method == "update-user"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'nationalcode' => "required",
        'phone' => "required",
        'email' => "required|email",
        'password' => "required",
        'status' => "required",
    ] , 
    [
        'nationalcode:required' => customErrorMessage('کد ملی', 'required'),
        'phone:required' => customErrorMessage('شماره همراه', 'required'),
        'email:required' => customErrorMessage('پست الکترونیکی', 'required'),
        'email:email' => customErrorMessage('پست الکترونیکی', 'email'),
        'password:required' => customErrorMessage('گذرواژه', 'required'),
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

    $data = request()->data;
    
    $num_rows = $db->get('users', [], "nationalcode = '".$data->nationalcode. "' or email = '".$data->email."'")->num_rows;
    if($num_rows == 1 || $num_rows == 0){
        $stmt = $db->command()->prepare('UPDATE users SET nationalcode = ?, phone = ?, email = ?, status = ? WHERE id = ?');
        $stmt->bind_param(
            'issii', 
            $data->nationalcode,
            $data->phone,
            $data->email,
            $data->status,
            $data->id
        );
    
        $stmt->execute();
        
        $response = (object) [
            'message' => "عملیات با موفقیت انجام شد",
            'code' => 201
        ];
    }else{
        $response = (object) [
            'message' => "کاربری با این مشخصات از قبل وجود دارد",
            'code' => 400
        ];
    }
    
    

    return response_json($response, $response->code);
    
}

if(request()->get->method == 'remove-profile-picture'){
    $user_id = request()->data->user_id;

    $res = $db->query("SELECT * FROM users where id = $user_id");
    $info = $res->fetch_object();
    $result = 0;
    if($info->image_path){
        if(unlink('../'.$info->image_path)){
            $db->query("UPDATE users set image_path = null WHERE id = $user_id");
            $result = 1;
        }
    }

    $response = (object)[
        'message' => ($result) ? 'تصویر پروفایل شما حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($result) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-user"){
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
    $db->query("DELETE FROM users_groups WHERE user_id IN($ids)");
    $db->query("DELETE FROM users_access WHERE user_id IN($ids)");
    $db->query("DELETE FROM users_roles WHERE user_id IN($ids)");
    $db->query("DELETE FROM auth_token WHERE user_id IN($ids)");
    $db->query("DELETE FROM intern_recruitment_application WHERE user_id IN($ids)");
    $db->query("DELETE FROM company_registration_application WHERE user_id IN($ids)");
    $db->query("DELETE FROM stu_semesters WHERE user_id IN($ids)");
    $db->query("DELETE FROM stu_request WHERE user_id IN($ids)");
    $db->query("DELETE FROM stu_request WHERE teacher IN($ids)");
    $res = $db->query("DELETE FROM users where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'کاربر با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "reset-password"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'id' => "required",
    ] , 
    [
        'id:required' => customErrorMessage('آیدی کاربر', 'required'),
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
    
    $user_id = request()->data->id;
    $res = $db->get('users', [], "id = $user_id");
    $num_rows = $res->num_rows;

    if($num_rows > 0){
        $hashed_password = password_hash('0'.$res->fetch_object()->nationalcode, PASSWORD_DEFAULT);

        $update_res = $db->query("UPDATE users SET password = '$hashed_password' WHERE id = $user_id");
        
        if($update_res)
            $response = (object)[
                "message" => "گذرواژه ریست گردید و کد ملی جایگزین آن شد",
                "code" => 200
            ];
        else
            $response = (object)[
                "message" => "عملیات با خطا مواجه گردید",
                "code" => 400
            ];

    }else{
        $response = (object)[
            "message" => "عملیات با خطا مواجه گردید",
            "code" => 400
        ];
    }

    return response_json($response, $response->code);
}

if(request()->get->method == "get-user-access"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'role_id' => "required",
    ] , 
    [
        'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        'role_id:required' => customErrorMessage('آیدی نقش', 'required'),
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
    $role_id = request()->data->role_id;
    
    $user_res = $db->get('users_access', [], "user_id = $user_id and status = 1");
    $role_res = $db->get('roles_access', [], "role_id = $role_id and status = 1");
    
    if($user_res->num_rows > 0 || $role_res->num_rows > 0){
        $user_list = withForArray($user_res->fetch_all(MYSQLI_ASSOC),
            [
                "users" => ['primary_key' => 'id', 'foreign_key' => 'user_id', 'model_name' => 'user'],
                'menu' => ['primary_key' => 'id', 'foreign_key' => 'menu_id', 'model_name' => 'menu']
            ]
        );
        $role_list = withForArray($role_res->fetch_all(MYSQLI_ASSOC), 
            [
                "roles" => ['primary_key' => 'id', 'foreign_key' => 'role_id', 'model_name' => 'role'],
                'menu' => ['primary_key' => 'id', 'foreign_key' => 'menu_id', 'model_name' => 'menu']
            ]
        );
        
        $response = (object)[
            'row' => array_merge($user_list, $role_list),
            'message' => 'دسترسی کاربر با موفقیت ارسال گردید',
            'code' => 200
        ];

    }else{

        $response = (object)[
            'row' => [],
            'message' => 'عملیات با خطا مواجه گردید',
            'code' => 400 
        ];
        
    }

    return response_json($response, $response->code);
}