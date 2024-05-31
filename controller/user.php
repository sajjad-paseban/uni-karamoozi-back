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
            "info" => $info
        ],
        "message" => "اطلاعات دریافت شد",
        "code" => 200
    ];

    return response_json($response, $response->code);
}
