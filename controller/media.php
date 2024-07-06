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

        $res = $db->get('media',[], "id = $id");

        $media_list = $res->fetch_object();

    }else{

        $res = $db->get('media',[]);

        $media_list = $res->fetch_all(MYSQLI_ASSOC);
        
    }
    

    
    

    $response = (object)[
        'row' => [
            'media_list' => $media_list,
        ],
        'message' => 'لیست محتوا ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if(request()->get->method == "create-media"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'title' => "required",
        'image' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان (alt)', 'required'),
        'image:required' => customErrorMessage('آپلود تصویر', 'required'),
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
    $filename = 'media-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = '../storage/media/'.$filename;
    $db_path = 'storage/media/'.$filename;
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $path)){
        $stmt = $db->command()->prepare("INSERT INTO media(alt, path) VALUES(?,?)");
        $stmt->bind_param(
            'ss',
            request()->data->alt,
            $db_path
        );
        
        $res = $stmt->execute();
    }else{
        $res = false;
    }

    $response = (object)[
        'message' => ($res) ? 'محتوا با موفقیت اضافه گردید' : 'عملیات با خطا مواجه گردید',
        'id' => ($res) ? $stmt->insert_id : null,
        'title' => ($res) ? request()->data->title : null,
        'url' => ($res) ? $db_path : null,
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);    
}

if(request()->get->method == "update-media"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'title' => "required",
        'seo_description' => "required",
        'media' => "required",
        'status' => "required",
        'has_star' => "required",
    ] , 
    [
        'title:required' => customErrorMessage('عنوان محتوا', 'required'),
        'seo_description:required' => customErrorMessage('توضیحات محتوا', 'required'),
        'media:required' => customErrorMessage('محتوا', 'required'),
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
        $filename = 'media-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
        $path = '../storage/media/'.$filename;
        $db_path = 'storage/media/'.$filename;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $path)){
            $stmt = $db->command()->prepare("UPDATE media SET title = ?, banner_path = ?, seo_description = ?, media = ?, status = ?, has_star = ? WHERE id = ?");
            $stmt->bind_param(
                'ssssiii',
                request()->data->title,
                $db_path,
                request()->data->seo_description,
                request()->data->media,
                request()->data->status,
                request()->data->has_star,
                request()->data->id,
            );
            
            $res = $stmt->execute();
        }else{
            $res = false;
        }
    }else{
        $stmt = $db->command()->prepare("UPDATE media SET title = ?, seo_description = ?, media = ?, status = ?, has_star = ? WHERE id = ?");
        $stmt->bind_param(
            'sssiii',
            request()->data->title,
            request()->data->seo_description,
            request()->data->media,
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

if(request()->get->method == "delete-media"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data, 
    [
        'id' => "required",
    ] , 
    [
        'id:required' => customErrorMessage('آیدی', 'required'),
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
    
    $id = request()->data->id;
    $res = $db->query("DELETE FROM media where id = $id");   
    
    $response = (object)[
        'message' => ($res) ? 'محتوا با موفقیت حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}