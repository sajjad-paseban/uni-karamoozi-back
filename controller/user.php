<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;

middleware_user_login(request()->data);

$db = new DB();

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
            "info" => param_hidden($info, ['password'])
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
        birthdate = $birthdate,
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