<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;


// middleware_user_login(request()->data);

$db = new DB();

if(request()->get->method == "cvt-manager"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'group_id' => "required",
        'file' => "required",
    ] , 
    [
        'group_id:required' => customErrorMessage('گروه', 'required'),
        'file:required' => customErrorMessage('فایل', 'required'),
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

    $res = false;

    $file = $_FILES['file'];
    $filename = 'cvt-manager-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = '../storage/convert/'.$filename;

    $status = 1;
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $path)){
        $managers = (new FastExcel)->import($path, function ($line) use($db) {
            $fname = $line['fname'];
            $lname = $line['lname'];
            $nationalcode = $line['nationalcode'];
            $phone = '0'. $line['phone'];
            $email = $line['email'];
            $result = $db->get("users", [], "nationalcode = $nationalcode or email = '$email'");
            if($result->num_rows == 0){
                $stmt = $db->command()->prepare("INSERT INTO users(fname,lname,nationalcode,email,phone,password,status) VALUES(?,?,?,?,?,?,?)");
                $hashed_password = password_hash($nationalcode, PASSWORD_DEFAULT);
                $stmt->bind_param(
                    'ssisssi', 
                    $fname,
                    $lname,
                    $nationalcode,
                    $email,
                    $phone,
                    $hashed_password,
                    $status,
                );
            
                $stmt->execute();
                
                //role
                $us_id = $stmt->insert_id;
                $ro_id = env('DEFAULT_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();
                $ro_id = env('MANAGER_ROLE');
                $active = 0;
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();

                //user-group
                $ro_id = env('MANAGER_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                $stmt->bind_param(
                    'iiii',
                    $ro_id,
                    $us_id,
                    request()->data->group_id,
                    $active,
                );
                
                $stmt->execute();
            }else{
                $user_id = $result->fetch_object()->id;
                $role_id = env('MANAGER_ROLE');
                $role_res = $db->get('users_roles', [], "user_id = $user_id and role_id = $role_id");
                if($role_res->num_rows == 0){
                    //role
                    $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");                    
                    $active = 0;
                    $stmt->bind_param(
                        'iii', 
                        $role_id,
                        $user_id,
                        $active
                    );  
                    $stmt->execute();

                    //user-group
                    $active = 1;
                    $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                    $stmt->bind_param(
                        'iiii',
                        $role_id,
                        $user_id,
                        request()->data->group_id,
                        $active,
                    );
                    
                    $stmt->execute();
                }else{
                    $group_id = request()->data->group_id;
                    $ug_res = $db->get('users_groups', [], "role_id = $role_id and user_id = $user_id and group_id = $group_id");
                    if($ug_res->num_rows == 0){
                        //user-group
                        $active = 1;
                        $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                        $stmt->bind_param(
                            'iiii',
                            $role_id,
                            $user_id,
                            request()->data->group_id,
                            $active,
                        );
                        
                        $stmt->execute();
                    }
                }
            }
            
        });
        
        $res = true;

    }else{
        
        $res = false;
    }
    unlink($path);
    $response = (object)[
        'message' => ($res) ? 'کانورت با موفقیت انجام شد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];
    
    return response_json($response, $response->code);
}

if(request()->get->method == "cvt-teacher"){
    $validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'group_id' => "required",
        'file' => "required",
    ] , 
    [
        'group_id:required' => customErrorMessage('گروه', 'required'),
        'file:required' => customErrorMessage('فایل', 'required'),
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

    $res = false;

    $file = $_FILES['file'];
    $filename = 'cvt-teacher-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = '../storage/convert/'.$filename;

    if(move_uploaded_file($_FILES["file"]["tmp_name"], $path)){
        $managers = (new FastExcel)->import($path, function ($line) use($db) {
            $status = 1;
            $fname = $line['fname'];
            $lname = $line['lname'];
            $nationalcode = $line['nationalcode'];
            $phone = '0'. $line['phone'];
            $email = $line['email'];
            $result = $db->get("users", [], "nationalcode = $nationalcode or email = '$email'");
            if($result->num_rows == 0){
                $stmt = $db->command()->prepare("INSERT INTO users(fname,lname,nationalcode,email,phone,password,status) VALUES(?,?,?,?,?,?,?)");
                $hashed_password = password_hash($nationalcode, PASSWORD_DEFAULT);
                $stmt->bind_param(
                    'ssisssi', 
                    $fname,
                    $lname,
                    $nationalcode,
                    $email,
                    $phone,
                    $hashed_password,
                    $status,
                );
            
                $stmt->execute();
                
                //role
                $us_id = $stmt->insert_id;
                $ro_id = env('DEFAULT_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();
                $ro_id = env('TEACHER_ROLE');
                $active = 0;
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();

                //user-group
                $ro_id = env('TEACHER_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                $stmt->bind_param(
                    'iiii',
                    $ro_id,
                    $us_id,
                    request()->data->group_id,
                    $active,
                );
                
                $stmt->execute();
            }else{
                $user_id = $result->fetch_object()->id;
                $role_id = env('TEACHER_ROLE');
                $role_res = $db->get('users_roles', [], "user_id = $user_id and role_id = $role_id");
                if($role_res->num_rows == 0){
                    //role
                    $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");                    
                    $active = 0;
                    $stmt->bind_param(
                        'iii', 
                        $role_id,
                        $user_id,
                        $active
                    );  
                    $stmt->execute();

                    //user-group
                    $active = 1;
                    $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                    $stmt->bind_param(
                        'iiii',
                        $role_id,
                        $user_id,
                        request()->data->group_id,
                        $active,
                    );
                    
                    $stmt->execute();
                }else{
                    $group_id = request()->data->group_id;
                    $ug_res = $db->get('users_groups', [], "role_id = $role_id and user_id = $user_id and group_id = $group_id");
                    if($ug_res->num_rows == 0){
                        //user-group
                        $active = 1;
                        $stmt = $db->command()->prepare("INSERT INTO users_groups(role_id, user_id, group_id, status) VALUES(?,?,?,?)");
                        $stmt->bind_param(
                            'iiii',
                            $role_id,
                            $user_id,
                            request()->data->group_id,
                            $active,
                        );
                        
                        $stmt->execute();
                    }
                }
            }
            
        });
        
        $res = true;

    }else{
        $res = false;
    }
    unlink($path);
    $response = (object)[
        'message' => ($res) ? 'کانورت با موفقیت انجام شد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];
    
    return response_json($response, $response->code);
}

if(request()->get->method == "cvt-student"){
$validator = new Validator();

    $validation = $validator->make((array) request()->data + $_FILES, 
    [
        'group_id' => "required",
        'semester_id' => "required",
        'file' => "required",
    ] , 
    [
        'group_id:required' => customErrorMessage('گروه', 'required'),
        'semester_id:required' => customErrorMessage('نیمسال تحصیلی', 'required'),
        'file:required' => customErrorMessage('فایل', 'required'),
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

    $res = false;

    $file = $_FILES['file'];
    $filename = 'cvt-student-' .Carbon::now()->format('Y-m-d-H-i-s'). '.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    $path = '../storage/convert/'.$filename;

    if(move_uploaded_file($_FILES["file"]["tmp_name"], $path)){
        $managers = (new FastExcel)->import($path, function ($line) use($db) {
            $status = 1;
            $fname = $line['fname'];
            $lname = $line['lname'];
            $nationalcode = '0'.$line['nationalcode'];
            $phone = '0'. $line['phone'];
            $email = $line['email'];
            $result = $db->get("users", [], "nationalcode = $nationalcode or email = '$email'");
            if($result->num_rows == 0){
                $stmt = $db->command()->prepare("INSERT INTO users(fname,lname,nationalcode,email,phone,password,status) VALUES(?,?,?,?,?,?,?)");
                $hashed_password = password_hash($nationalcode, PASSWORD_DEFAULT);
                $stmt->bind_param(
                    'ssisssi', 
                    $fname,
                    $lname,
                    $nationalcode,
                    $email,
                    $phone,
                    $hashed_password,
                    $status,
                );
            
                $stmt->execute();
                
                //role
                $us_id = $stmt->insert_id;
                $ro_id = env('DEFAULT_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();
                $ro_id = env('STUDENT_ROLE');
                $active = 0;
                $stmt->bind_param(
                    'iii', 
                    $ro_id,
                    $us_id,
                    $active
                );  
                $stmt->execute();

                //stu-semester
                $ro_id = env('STUDENT_ROLE');
                $active = 1;
                $stmt = $db->command()->prepare("INSERT INTO stu_semesters(semester_id, user_id, group_id, status) VALUES(?,?,?,?)");
                $stmt->bind_param(
                    'iiii',
                    request()->data->semester_id,
                    $us_id,
                    request()->data->group_id,
                    $active,
                );
                
                $stmt->execute();
            }else{
                $user_id = $result->fetch_object()->id;
                $role_id = env('STUDENT_ROLE');
                $role_res = $db->get('users_roles', [], "user_id = $user_id and role_id = $role_id");
                if($role_res->num_rows == 0){
                    //role
                    $stmt = $db->command()->prepare("INSERT INTO users_roles(role_id, user_id, default_role) VALUES(?,?,?)");                    
                    $active = 0;
                    $stmt->bind_param(
                        'iii', 
                        $role_id,
                        $user_id,
                        $active
                    );  
                    $stmt->execute();

                    //stu-semester
                    $active = 1;
                    $stmt = $db->command()->prepare("INSERT INTO stu_semesters(semester_id, user_id, group_id, status) VALUES(?,?,?,?)");
                    $stmt->bind_param(
                        'iiii',
                        request()->data->semester_id,
                        $user_id,
                        request()->data->group_id,
                        $active,
                    );
                    
                    $stmt->execute();
                }else{
                    $semester_id = request()->data->semester_id;
                    $group_id = request()->data->group_id;
                    $ss_res = $db->get('stu_semesters', [], "semester_id = $semester_id and user_id = $user_id and group_id = $group_id");
                    if($ss_res->num_rows == 0){
                        //stu-semester
                        $active = 1;
                        $stmt = $db->command()->prepare("INSERT INTO stu_semesters(semester_id, user_id, group_id, status) VALUES(?,?,?,?)");
                        $stmt->bind_param(
                            'iiii',
                            request()->data->semester_id,
                            $user_id,
                            request()->data->group_id,
                            $active,
                        );
                        
                        $stmt->execute();
                    }
                }
            }
            
        });
        
        $res = true;

    }else{
        $res = false;
    }
    unlink($path);
    $response = (object)[
        'message' => ($res) ? 'کانورت با موفقیت انجام شد' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400 
    ];
    
    return response_json($response, $response->code);
}