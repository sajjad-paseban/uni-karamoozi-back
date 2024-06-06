<?php 
require_once '../helper/middleware.php';
require_once '../helper/function.php';
require_once "../helper/includes.php";

use Rakit\Validation\Validator;
$db = new DB();

if(request()->get->method == "get-data"){
    $res = $db->get('setting', []);

    $response = (object)[
        "row" => $res->fetch_object(),
        "message" => 'اطلاعات سایت فرستاده شد',
        "code" => 200
    ];
    
    return response_json($response, $response->code);
}


if(request()->get->method == "remove-logo"){
    middleware_user_login(request()->data);

    $res = $db->get('setting', []);
    $info = $res->fetch_object();
    $result = 0;
    if($info->uni_logo_path){
        if(unlink('../'.$info->uni_logo_path)){
            $db->query("UPDATE setting set uni_logo_path = null WHERE id = 1");
            $result = 1;
        }
    }

    $response = (object)[
        'message' => ($result) ? 'لوگو دانشگاه حذف گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($result) ? 200 : 400 
    ];

    return response_json($response, $response->code);
}


if(request()->get->method == "update-data"){
    middleware_user_login(request()->data);
    
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'uni_name' => "required",
        'uni_logo_path' => "required",
        'footer_description' => "required",
        'long' => "required",
        'lat' => "required",
        'telephone' => "required",
        'email' => "required|email",
        'fax' => "required",
        'address' => "required",
        'description' => "required",
        'register_rules' => "required",
        'status' => "required",
    ] , 
    [
        'uni_name:required' => customErrorMessage('نام دانشگاه', 'required'),
        'uni_logo_path:required' => customErrorMessage('لوگو دانشگاه', 'required'),
        'footer_description:required' => customErrorMessage('', 'required'),
        'long:required' => customErrorMessage('موقعیت دانشگاه', 'required'),
        'lat:required' => customErrorMessage('موقعیت دانشگاه', 'required'),
        'telephone:required' => customErrorMessage('شماره تماش', 'required'),
        'email:required' => customErrorMessage('پست الکترونیکی', 'required'),
        'email:email' => customErrorMessage('پست الکترونیکی', 'email'),
        'fax:required' => customErrorMessage('شماره فکس', 'required'),
        'address:required' => customErrorMessage('آدرس', 'required'),
        'description:required' => customErrorMessage('توضیحات', 'required'),
        'register_rules:required' => customErrorMessage('قوانین ثبت نام', 'required'),
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

    
    if(isset($_FILES['uni_logo_path'])){

        $file = $_FILES['uni_logo_path'];
        $filename = 'site_logo.'.pathinfo($file['name'], PATHINFO_EXTENSION);
        $path =  '../storage/logo/'.$filename;
        $db_path =  'storage/logo/'.$filename;
        
        if(move_uploaded_file($_FILES["uni_logo_path"]["tmp_name"], $path)){
            $upload_res = true;
            $db->query("UPDATE setting SET uni_logo_path = '$db_path' where id = 1");
        }
        else{
            $upload_res = false;
        }
    }
    
    $stmt = $db->command()->prepare("UPDATE setting 
    SET 
    uni_name = ?,
    footer_description = ?,
    location = ?,
    telephone = ?,
    email = ?,
    fax = ?,
    address = ?,
    description = ?,
    register_rules = ?,
    status = ?
    where id = 1
    ");

    $lat = request()->data->lat;
    $long = request()->data->long;
    $location = '{"lat": "'. $lat .'", "long": "'. $long .'"}';
    
    $stmt->bind_param(
        'sssssssssi',
        request()->data->uni_name,
        request()->data->footer_description,
        $location,
        request()->data->telephone,
        request()->data->email,
        request()->data->fax,
        request()->data->address,
        request()->data->description,
        request()->data->register_rules,
        request()->data->status,
    );

    $stmt->execute();

    
    $response = (object) [
        'message' => "اطلاعات سایت بروزرسانی گردید",
        'code' => 200
    ];
    return response_json($response, $response->code);
    
}