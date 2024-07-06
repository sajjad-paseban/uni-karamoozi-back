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