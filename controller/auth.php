<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;


$db = new DB();

if(request()->get->method == "register"){
    $data = request()->post;
    
    $num_rows = $db->get('users', [], "nationalcode = '".$data->national_code. "' or email = '".$data->email."'")->num_rows;
    if($num_rows == 0){
        $stmt = $db->command()->prepare('insert into users(nationalcode, phone, email, password) values(?,?,?,?)');
        $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt->bind_param(
            'isss', 
            $data->national_code,
            $data->phone,
            $data->email,
            $hashed_password
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

if(request()->get->method == "login"){
    $validator = new Validator();

    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'national_code'  => 'required',
        'password'       => 'required',
    ]);

    $validation->setMessages([
        "national_code:required" => 'فیلد کد ملی اجباری می باشد',
        "password:required" => 'فیلد گذرواژه اجباری می باشد'
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

    $nationalcode = request()->data->national_code;
    $password = request()->data->password;
    
    $res = $db->get('users',[], "nationalcode = $nationalcode");
    $info = $res->fetch_object();

    if($res->num_rows == 1 && $info->status && password_verify($password, $info->password)){
        
        $auth_token = $db->get('auth_token',[], "user_id = $info->id and type = 1 and status = 1")->fetch_object();
        if($auth_token){
            $response = (object)[
                "message" => "کاربر session فعال دارد",
                "code" => 400
            ];

            return response_json($response, $response->code);
        }

        $token = bin2hex($info->id.random_bytes(16).'token');
        $type = 1;
        $status = true;
        $date = Carbon::now()->addDays(2);        
        
        $stmt = $db->command()->prepare("insert into auth_token(user_id, token, type, status, expire_date) values(?,?,?,?,?)");
        $stmt->bind_param(
            "isiis",
            $info->id,
            $token,
            $type,
            $status,
            $date
        );
        
        $stmt->execute();

        $response = (object)[
            "row" => [
                "user_info" => param_hidden($info, ['password']),
                "token" => $token
            ],
            "message" => "درحال انتقال به پنل کاربری می باشید",
            "code" => 200
        ];
    }
    else{
        $response = (object)[
            "row" => null,
            "message" => "کد ملی یا گذرواژه اشتباه می باشد.",
            "code" => 401
        ];
    }


    return response_json($response, $response->code);


}

if(request()->get->method == "check-auth-expiration"){
    $validator = new Validator();

    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'user_id'  => 'required',
        'token'       => 'required',
    ]);

    $validation->setMessages([
        "user_id:required" => 'آیدی کاربر می بایست ارسال شود',
        "token:required" => 'token می بایست ارسال شود'
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
    $token = request()->data->token;
    
    
    $res = $db->get('auth_token',[], "user_id = $user_id and token = '$token' and type = 1 and status = 1");

    if($res->num_rows == 1){
        
        $auth_token = $res->fetch_object();
        $diff = Carbon::now()->diffInDays(Carbon::parse($auth_token->expire_date)); 
        
        if($diff < 0){
            $db->query("UPDATE auth_token SET status = 0 WHERE user_id = $user_id and token = '$token'");
            
            $response = (object)[
                "check" => false,
                "message" => "token منقضی شده است",
                "code" => 401
            ];    
        }else{
            $response = (object)[
                "check" => true,
                "message" => "token معتبر می باشد",
                "code" => 200
            ];
        }
    }
    else{
        $response = (object)[
            "check" => false,
            "message" => "همچین token ای وجود ندارد",
            "code" => 401
        ];
    }


    return response_json($response, $response->code);


}

if(request()->get->method == "logout"){
    $validator = new Validator();

    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'user_id'  => 'required',
        'token'       => 'required',
    ]);

    $validation->setMessages([
        "user_id:required" => 'آیدی کاربر می بایست ارسال شود',
        "token:required" => 'token می بایست ارسال شود'
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
    $token = request()->data->token;
    
    
    $res = $db->get('auth_token',[], "user_id = $user_id and token = '$token' and type = 1 and status = 1");
    $info = $res->fetch_object();

    if($res->num_rows == 1){
        
        $db->query("UPDATE auth_token SET status = 0 where user_id = $user_id and token = '$token'");

        $response = (object)[
            "message" => "خروج از سامانه با موفقیت انجام شد",
            "code" => 200
        ];
    }
    else{
        $response = (object)[
            "row" => null,
            "message" => "همچین session ای وجود ندارد",
            "code" => 401
        ];
    }


    return response_json($response, $response->code);


}

if(request()->get->method == "change-password"){
    middleware_user_login(request()->data);


    $validator = new Validator();
    
    $validation = $validator->make((array)request()->get + (array)request()->post, [
        'password'  => 'required|min:8',
        're_password'       => 'required|same:password',
    ]);

    $validation->setMessages([
        "password:required" => 'فیلد کلمه عبور اجباری می باشد',
        "password:min" => 'فیلد کلمه عبور می بایست حداقل هشت رقمی باشد',
        "re_password:required" => 'فیلد تکرار کلمه عبور اجباری می باشد',
        "re_password:same" => 'فیلد کلمه عبور با تکرار کلمه عبور یکسان نمی باشد'
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
    $hashed_password = password_hash(request()->data->password, PASSWORD_DEFAULT);
    $res = $db->query("UPDATE users set password = '$hashed_password' where id = $user_id");
    if($res){
        $response = (object)[
            "message" => "کلمه عبور تغییر کرد",
            "code" => 200
        ];
    }else{
        $response = (object)[
            "message" => "عملیات با خطا مواجه شد",
            "code" => 400
        ];
    }
    
    return response_json($response, $response->code);
}

if(request()->get->method == "check-user-has-access"){
    
    $validator = new Validator();
    
    $validation = $validator->make((array) request()->data, 
    [
        'user_id' => "required",
        'role_id' => "required",
        'path' => 'required'
    ] , 
    [
        'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        'role_id:required' => customErrorMessage('آیدی نقش', 'required'),
        "path:required" => 'مسیر می بایست ارسال شود',
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
        
        $all = array_merge($user_list, $role_list);

        $flag = false;
        foreach($all as $item){
            $item = (object) $item;

            if($item->menu->status == 1 && str_contains(request()->data->path, $item->menu->key_param)){
                $flag = true;
            }
        }

        $response = (object)[
            'res' => $flag,
            'message' => ($flag) ? 'دسترسی وجود دارد' : 'دسترسی وجود ندارد',
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