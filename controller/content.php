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

        $res = $db->get('content',[], "id = $id");

        $content_list = $res->fetch_object();

    }else{

        $res = $db->get('content',[]);

        $content_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'content_list' => $content_list,
        ],
        'message' => 'لیست محتوا ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-content"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'title' => "required",
        'image' => "required",
        'seo_description' => "required",
        'content' => "required",
        'status' => "required",
        'has_star' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان محتوا', 'required'),
        'image:required' => customErrorMessage('آپلود تصویر', 'required'),
        'seo_description:required' => customErrorMessage('توضیحات محتوا', 'required'),
        'content:required' => customErrorMessage('محتوا', 'required'),
        'status:required' => customErrorMessage('وضعیت', 'required'),
        'has_star:required' => customErrorMessage('مهم', 'required'),
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
    $filename = 'content-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = '../storage/content/'.$filename;
    $db_path = 'storage/content/'.$filename;
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $path)){
        $stmt = $db->command()->prepare("INSERT INTO content(title, banner_path, seo_description, content, status, has_star) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param(
            'ssssii',
            request()->data->title,
            $db_path,
            request()->data->seo_description,
            request()->data->content,
            request()->data->status,
            request()->data->has_star,
        );
        
        $res = $stmt->execute();
    }else{
        $res = false;
    }

    $response = (object)[
        'message' => ($res) ? 'محتوا با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-content"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'seo_description' => "required",
        'content' => "required",
        'status' => "required",
        'has_star' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان محتوا', 'required'),
        'seo_description:required' => customErrorMessage('توضیحات محتوا', 'required'),
        'content:required' => customErrorMessage('محتوا', 'required'),
        'status:required' => customErrorMessage('وضعیت', 'required'),
        'has_star:required' => customErrorMessage('مهم', 'required'),
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
    if(isset($_FILES['image'])){
        $file = $_FILES['image'];
        $filename = 'content-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
        $path = '../storage/content/'.$filename;
        $db_path = 'storage/content/'.$filename;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $path)){
            $stmt = $db->command()->prepare("UPDATE content SET title = ?, banner_path = ?, seo_description = ?, content = ?, status = ?, has_star = ? WHERE id = ?");
            $stmt->bind_param(
                'ssssiii',
                request()->data->title,
                $db_path,
                request()->data->seo_description,
                request()->data->content,
                request()->data->status,
                request()->data->has_star,
                request()->data->id,
            );
            
            $res = $stmt->execute();
        }else{
            $res = false;
        }
    }else{
        $stmt = $db->command()->prepare("UPDATE content SET title = ?, seo_description = ?, content = ?, status = ?, has_star = ? WHERE id = ?");
        $stmt->bind_param(
            'sssiii',
            request()->data->title,
            request()->data->seo_description,
            request()->data->content,
            request()->data->status,
            request()->data->has_star,
            request()->data->id,
        );
        
        $res = $stmt->execute();
    }

    $response = (object)[
        'message' => ($res) ? 'محتوا با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "delete-content"){
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
    $res = $db->query("DELETE FROM content where id in($ids)");   
    
    $response = (object)[
        'message' => ($res) ? 'محتوا با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}