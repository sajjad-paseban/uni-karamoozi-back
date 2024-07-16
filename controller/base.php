<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;

// middleware_user_login(request()->data);

$db = new DB();

if(request()->get->method == "landing-data"){
    
    $banners = $db->query("SELECT * FROM content WHERE status = 1 and has_star = 1 ORDER BY id desc LIMIT 5");   
    $news = $db->query("SELECT * FROM content WHERE status = 1 ORDER BY id desc LIMIT 20");
    $sites = $db->query("SELECT * FROM sites_management WHERE status = 1 ORDER BY id desc");
    
    $response = (object)[
        'row' => [
            'banners' => $banners->fetch_all(MYSQLI_ASSOC),
            'news' => $news->fetch_all(MYSQLI_ASSOC),
            'sites' => $sites->fetch_all(MYSQLI_ASSOC),
        ],
        'message' => 'اطلاعات با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-news"){
    
    $news = $db->query("SELECT * FROM content WHERE status = 1 ORDER BY id desc LIMIT 20");
    
    $response = (object)[
        'row' => [
            'news' => $news->fetch_all(MYSQLI_ASSOC),
        ],
        'message' => 'اطلاعات با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "find-content"){

    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'slug' => "required",
    ] , 
    [
        'slug:required' => customErrorMessage('اسلاگ', 'required'),
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

    $slug = request()->data->slug;
    
    $content = $db->get('content', [], "title = '$slug'")->fetch_object();
    
    $response = (object)[
        'row' => [
            'content' => $content
        ],
        'message' => 'لیست محتوا با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "get-companies"){
    $semester_ids = implode(',', array_column($db->get('semester', [], "is_active = 1")->fetch_all(MYSQLI_ASSOC), 'id'));

    $ira_list = $db->get('intern_recruitment_application', [], "semester_id in($semester_ids) and status = 1")->fetch_all(MYSQLI_ASSOC);
    
    $ira_list = withForArray($ira_list, [
        'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
        'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
        'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
        'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra']
    ]);

    foreach($ira_list as $key => $item){
        $id = $item['id'];
        $num_rows = $db->get('stu_request', [], "ira_id = $id and status = 1")->num_rows;
        $ira_list[$key]['last_capacity'] = $ira_list[$key]['capacity'] - $num_rows; 
        $ira_list[$key]['user'] = param_hidden($ira_list[$key]['user'], ['password']);
    }

    $response = (object)[
        'row' => [
            'companies' => $ira_list
        ],
        'message' => 'لیست شرکت ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}