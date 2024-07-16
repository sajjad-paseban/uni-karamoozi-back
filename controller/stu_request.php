<?php

require_once "../helper/middleware.php";
require_once "../helper/includes.php";
require_once '../helper/function.php';

use Rakit\Validation\Validator;
use Carbon\Carbon;

middleware_user_login(request()->data);

$db = new DB();

if (request()->get->method == "get-data") {

    if (isset(request()->data->id)) {

        $id = request()->data->id;

        $res = $db->get('stu_request', [], "id = $id");
        $num_rows = $res->num_rows;
        $requests = $res->fetch_object();
        

        $requests = withForObject($requests, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'stu_semesters' => ['foreign_key' => 'stu_semester_id', 'primary_key' => 'id', 'model_name' => 'stu_semester'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']
        ]);
        
        $requests->ira = withForObject($requests->ira, [
            'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra']
        ]);
        
        $requests = withForObject($requests, [
            'users' => ['foreign_key' => 'teacher', 'primary_key' => 'id', 'model_name' => 'teacher_user'],
        ]);

        $requests->user = param_hidden($requests->user, ['password']);
        $requests->teacher_user = param_hidden($requests->teacher_user, ['password']);
        
    } else {

        $requests = $db->get('stu_request', []);
        $num_rows = $res->num_rows;
        $requests = $res->fetch_all(MYSQLI_ASSOC);

        $requests = withForArray($requests, [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'stu_semesters' => ['foreign_key' => 'stu_semester_id', 'primary_key' => 'id', 'model_name' => 'stu_semester'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']
        ]);

        $requests = withForArray($requests, [
            'users' => ['foreign_key' => 'teacher', 'primary_key' => 'id', 'model_name' => 'teacher_user'],
        ]);
    }

    $response = (object)[
        'row' => [
            'requests' => $num_rows > 0 ? $requests : null,
        ],
        'message' => 'لیست درخواست ها با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "get-data-for-teacher") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    $user_id = request()->data->user_id;
    $semester_id = $db->get('semester', [], "is_active = 1")->fetch_object()->id;

    $requests = $db->get('stu_request', [], "teacher = $user_id and status = 1 and semester_id = $semester_id");

    $data = [];
    if($requests->num_rows > 0){
        $data = withForArray($requests->fetch_all(MYSQLI_ASSOC), [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'stu_semesters' => ['foreign_key' => 'stu_semester_id', 'primary_key' => 'id', 'model_name' => 'stu_semester'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']            
        ]);
        
        $data = withForArray($data, [
            'users' => ['foreign_key' => 'teacher', 'primary_key' => 'id', 'model_name' => 'teacher_user'],
        ]);

        foreach($data as $key => $item){
            $data[$key]['ira'] = withForObject($data[$key]['ira'], [
                'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra']
            ]);
            
            $data[$key]['type_desc'] = $data[$key]['type'] == 0 ? 'شرکت های مورد تایید دانشگاه' : 'سایر';
            $data[$key]['user'] = param_hidden($data[$key]['user'], ['password']);
            $data[$key]['teacher_user'] = param_hidden($data[$key]['teacher_user'], ['password']);
            $data[$key]['teacher_user']->fullname = $data[$key]['teacher_user']->fname . ' ' .$data[$key]['teacher_user']->lname;
            
        }
    }

    $response = (object)[
        'row' => [
            'requests' => $requests->num_rows > 0 ? $data : null,
        ],
        'message' => 'اطلاعات درخواست با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
    
}

if (request()->get->method == "get-data-for-manager") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    $user_id = request()->data->user_id;
    $semester_id = $db->get('semester', [], "is_active = 1")->fetch_object()->id;
    $group_ids = $db->get('users_groups', [], "status = 1 and role_id = 6 and user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
    $group_ids = implode(',' ,array_column($group_ids, 'group_id'));
    $requests = $db->get('stu_request', [], "group_id in($group_ids) and status = 1 and semester_id = $semester_id and teacher_confirm is not null");

    $data = [];
    if($requests->num_rows > 0){
        $data = withForArray($requests->fetch_all(MYSQLI_ASSOC), [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'stu_semesters' => ['foreign_key' => 'stu_semester_id', 'primary_key' => 'id', 'model_name' => 'stu_semester'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']            
        ]);
        
        $data = withForArray($data, [
            'users' => ['foreign_key' => 'teacher', 'primary_key' => 'id', 'model_name' => 'teacher_user'],
        ]);

        foreach($data as $key => $item){
            $data[$key]['ira'] = withForObject($data[$key]['ira'], [
                'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra']
            ]);
            
            $data[$key]['type_desc'] = $data[$key]['type'] == 0 ? 'شرکت های مورد تایید دانشگاه' : 'سایر';
            $data[$key]['user'] = param_hidden($data[$key]['user'], ['password']);
            $data[$key]['teacher_user'] = param_hidden($data[$key]['teacher_user'], ['password']);
            $data[$key]['teacher_user']->fullname = $data[$key]['teacher_user']->fname . ' ' .$data[$key]['teacher_user']->lname;
            
        }
    }

    $response = (object)[
        'row' => [
            'requests' => $requests->num_rows > 0 ? $data : null,
        ],
        'message' => 'اطلاعات درخواست با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
    
}

if (request()->get->method == "find-data-by-user-id") {
    
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $user_id = request()->data->user_id;
    $semester_id = $db->get('semester', [], "is_active = 1")->fetch_object()->id;
    $group_id = $db->get('stu_semesters', [], "semester_id = $semester_id and user_id = $user_id and status = 1")->fetch_object()->group_id;
    $requests = $db->get('stu_request', [], "semester_id = $semester_id and user_id = $user_id and group_id = $group_id and status = 1");

    $num_rows = $requests->num_rows;

    if($num_rows > 0){

        $requests = withForObject($requests->fetch_object(), [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'stu_semesters' => ['foreign_key' => 'stu_semester_id', 'primary_key' => 'id', 'model_name' => 'stu_semester'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']
        ]);
        
        $requests->ira = withForObject($requests->ira, [
            'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra']
        ]);
        
        $requests = withForObject($requests, [
            'users' => ['foreign_key' => 'teacher', 'primary_key' => 'id', 'model_name' => 'teacher_user'],
        ]);

        $requests->user = param_hidden($requests->user, ['password']);
        $requests->teacher_user = param_hidden($requests->teacher_user, ['password']);
    
    }


    $response = (object)[
        'row' => [
            'requests' => $num_rows > 0 ? $requests : null,
        ],
        'message' => 'اطلاعات درخواست با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "user-has-access") {

    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    
    $semesters = $db->get('semester', [], "is_active = 1")->fetch_all(MYSQLI_ASSOC);
    $semesters = implode(',',array_column($semesters, 'id'));
    $user_id = request()->data->user_id;
    $stu_semester = $db->get('stu_semesters', [], "status = 1 and user_id = $user_id and semester_id in($semesters)");
    $data = $stu_semester->fetch_object();
    $response = (object)[
        'row' => [
            'has_access' => $stu_semester->num_rows > 0 ? true : false,
            'stu_semester_id' => $stu_semester->num_rows > 0 ? $data->id : null,
            'group_id' => $stu_semester->num_rows > 0 ? $data->group_id : null
        ],
        'message' => 'لیست نیمسال های تحصیلی فعال با موفقیت ارسال شد',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "get-active-semesters") {
    $semesters = $db->get("semester", [], 'is_active = 1')->fetch_all(MYSQLI_ASSOC);
    
    $response = (object)[
        'row' => [
            'active_semesters' => $semesters,
        ],
        'message' => 'لیست نیمسال های تحصیلی فعال با موفقیت ارسال شد',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "get-companies") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $semester_id = $db->get('semester', [], "is_active = 1")->fetch_object()->id;
    $user_id = request()->data->user_id;
    $group_ids = $db->get('stu_semesters', [], "status = 1 and semester_id = $semester_id and user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
    $group_ids = implode(',',array_column($group_ids, 'group_id'));
    $company = $db->get('intern_recruitment_application',[], "group_id in($group_ids) and semester_id = $semester_id")->fetch_all(MYSQLI_ASSOC);
    $company = withForArray($company, 
    [
        'company_registration_application' => ['foreign_key' => 'cra_id', 'primary_key' => 'id', 'model_name' => 'cra'],
    ]);

    foreach($company as $key => $item){
        $id = $item['id'];
        $num_rows = $db->get('stu_request', [], "ira_id = $id and status = 1 and semester_id = $semester_id")->num_rows;
        $company[$key]['capacity'] = $company[$key]['capacity'] - $num_rows; 
    }

    $response = (object)[
        'row' => [
            'company_list' => $company,
        ],
        'message' => 'لیست شرکت ها با موفقیت ارسال شد',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "get-teachers") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $semester_id = $db->get('semester', [], "is_active = 1")->fetch_object()->id;
    $user_id = request()->data->user_id;
    $group_ids = $db->get('stu_semesters', [], "status = 1 and semester_id = $semester_id and user_id = $user_id")->fetch_all(MYSQLI_ASSOC);
    $group_ids = implode(',', array_column($group_ids, 'group_id'));
    $teacher_role = env("TEACHER_ROLE");
    $teachers = $db->get('users_groups', [], "group_id in($group_ids) and status = 1 and role_id = $teacher_role")->fetch_all(MYSQLI_ASSOC);
    $teachers = withForArray($teachers,
    [
        'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
    ]);
    
    $response = (object)[
        'row' => [
            'teacher_list' => $teachers,
        ],
        'message' => 'لیست شرکت ها با موفقیت ارسال شد',
        'code' => 200
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "create-request") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    
    $code = request()->data->code ?? null;
    $semester_id = request()->data->semester_id ?? null;
    $group_id = request()->data->group_id ?? null;
    $stu_semester_id = request()->data->stu_semester_id ?? null;
    $teacher = request()->data->teacher ?? null;
    $type = request()->data->type ?? null;
    $ira_id = request()->data->ira_id ?? null;
    $intern_name = request()->data->intern_name ?? null;
    $intern_phone = request()->data->intern_phone ?? null;
    $intern_telephone = request()->data->intern_telephone ?? null;
    $place_name = request()->data->place_name ?? null;
    $place_telephone = request()->data->place_telephone ?? null;
    $place_address = request()->data->place_address ?? null;
    $supervisor_name = request()->data->supervisor_name ?? null;
    $supervisor_phone = request()->data->supervisor_phone ?? null;
    $from_date = request()->data->from_date ?? null;
    $to_date = request()->data->to_date ?? null;
    $sat = request()->data->sat ?? null;
    $sat_from = request()->data->sat_from ?? null;
    $sat_to = request()->data->sat_to ?? null;
    $sun = request()->data->sun ?? null;
    $sun_from = request()->data->sun_from ?? null;
    $sun_to = request()->data->sun_to ?? null;
    $mon = request()->data->mon ?? null;
    $mon_from = request()->data->mon_from ?? null;
    $mon_to = request()->data->mon_to ?? null;
    $tue = request()->data->tue ?? null;
    $tue_from = request()->data->tue_from ?? null;
    $tue_to = request()->data->tue_to ?? null;
    $wed = request()->data->wed ?? null;
    $wed_from = request()->data->wed_from ?? null;
    $wed_to = request()->data->wed_to ?? null;
    $thu = request()->data->thu ?? null;
    $thu_from = request()->data->thu_from ?? null;
    $thu_to = request()->data->thu_to ?? null;
    $description = request()->data->description ?? null;

    $stmt = $db->command()->prepare("INSERT INTO stu_request(
        code,
        user_id,
        semester_id,
        group_id,
        stu_semester_id,
        teacher,
        type,
        ira_id,
        intern_name,
        intern_phone,
        intern_telephone,
        from_date,
        to_date,
        place_name,
        place_telephone,
        place_address,
        supervisor_name,
        supervisor_phone,
        sat,
        sat_from,
        sat_to,
        sun,
        sun_from,
        sun_to,
        mon,
        mon_from,
        mon_to,
        tue,
        tue_from,
        tue_to,
        wed,
        wed_from,
        wed_to,
        thu,
        thu_from,
        thu_to,
        description
        ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param(
        'siiiiiiissssssssssississississississs',
        $code,
        request()->data->user_id,
        $semester_id,
        $group_id,
        $stu_semester_id,
        $teacher,
        $type,
        $ira_id,
        $intern_name,
        $intern_phone,
        $intern_telephone,
        $from_date,
        $to_date,
        $place_name,
        $place_telephone,
        $place_address,
        $supervisor_name,
        $supervisor_phone,
        $sat,
        $sat_from,
        $sat_to,
        $sun,
        $sun_from,
        $sun_to,
        $mon,
        $mon_from,
        $mon_to,
        $tue,
        $tue_from,
        $tue_to,
        $wed,
        $wed_from,
        $wed_to,
        $thu,
        $thu_from,
        $thu_to,
        $description,
    );

    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'درخواست شما با موفقیت ثبت گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "update-city") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'title' => "required",
            'province_id' => "required",
            'status' => "required",
        ],
        [
            'title:required' => customErrorMessage('عنوان', 'required'),
            'province_id:required' => customErrorMessage('استان', 'required'),
            'status:required' => customErrorMessage('وضعیت', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $stmt = $db->command()->prepare("UPDATE city SET title = ?, province_id = ?, status = ? WHERE id = ?");
    $stmt->bind_param(
        'siii',
        request()->data->title,
        request()->data->province_id,
        request()->data->status,
        request()->data->id
    );

    $res = $stmt->execute();

    $response = (object)[
        'message' => ($res) ? 'شهر با موفقیت بروز رسانی گردید' : 'عملیات با خطا مواجه گردید',
        'code' => ($res) ? 200 : 400
    ];

    return response_json($response, $response->code);
}

if (request()->get->method == "delete-request") {
        
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'id' => "required",
        ],
        [
            'id:required' => customErrorMessage('آیدی', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    
    $id = request()->data->id;
    
    $requests = $db->get('stu_request', [], "id = $id and status = 1");

    if($requests->num_rows > 0){
        $data = $requests->fetch_object();
        if(($data->teacher_confirm != 1 || $data->teacher_confirm != null) && ($data->manager_confirm != 0 || $data->manager_confirm != null)){
            $db->query("DELETE FROM stu_request WHERE id = $id");
        
            $response = (object)[
                'message' => 'درخواست شما با موفقیت حذف گردید',
                'code' => 200
            ];                
        }else{
            $response = (object)[
                'message' => 'درخواست شما توسط استاد یا مدیر گروه رد نشده است',
                'code' => 400
            ];    
        }
        


    }else{
        $response = (object)[
            'message' => 'درحال حاضر هنوز درخواستی ثبت نکرده اید',
            'code' => 400
        ];    
    }

    return response_json($response, $response->code);
}

if (request()->get->method == "confirm-by-manager") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'id' => "required",
        ],
        [
            'id:required' => customErrorMessage('آیدی', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $id = request()->data->id;
    $res = $db->query("UPDATE stu_request SET teacher_confirm = 1, manager_confirm = 1 WHERE id = $id");

    $response = (object)[
        'message' => $res ? 'عملیات با موفقیت انجام شد' : 'عملیات با خطا مواجه شد',
        'code' => $res ? 200 : 400
    ];                

    return response_json($response, $response->code);

}

if (request()->get->method == "confirm-by-teacher") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'id' => "required",
        ],
        [
            'id:required' => customErrorMessage('آیدی', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $id = request()->data->id;

    $res = $db->query("UPDATE stu_request SET teacher_confirm = 1 WHERE id = $id");

    $response = (object)[
        'message' => $res ? 'عملیات با موفقیت انجام شد' : 'عملیات با خطا مواجه شد',
        'code' => $res ? 200 : 400
    ];                

    return response_json($response, $response->code);

}

if (request()->get->method == "reject-by-manager") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'id' => "required",
            'description' => "required",
        ],
        [
            'id:required' => customErrorMessage('آیدی', 'required'),
            'description:required' => customErrorMessage('توضیحات', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $id = request()->data->id;
    $description = request()->data->description;
    $res = $db->query("UPDATE stu_request SET teacher_confirm = 0, teacher_description = '$description', manager_confirm = 0, manager_description = '$description' WHERE id = $id");

    $response = (object)[
        'message' => $res ? 'عملیات با موفقیت انجام شد' : 'عملیات با خطا مواجه شد',
        'code' => $res ? 200 : 400
    ];                

    return response_json($response, $response->code);

}

if (request()->get->method == "reject-by-teacher") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'id' => "required",
            'description' => "required",
        ],
        [
            'id:required' => customErrorMessage('آیدی', 'required'),
            'description:required' => customErrorMessage('توضیحات', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }

    $id = request()->data->id;
    $description = request()->data->description;
    
    $res = $db->query("UPDATE stu_request SET teacher_confirm = 0, teacher_description = '$description' WHERE id = $id");

    $response = (object)[
        'message' => $res ? 'عملیات با موفقیت انجام شد' : 'عملیات با خطا مواجه شد',
        'code' => $res ? 200 : 400
    ];                

    return response_json($response, $response->code);

}

if (request()->get->method == "get-my-students") {
    $validator = new Validator();

    $validation = $validator->make(
        (array) request()->data,
        [
            'user_id' => "required",
        ],
        [
            'user_id:required' => customErrorMessage('آیدی کاربر', 'required'),
        ]
    );

    $validation->validate();

    if ($validation->fails()) {
        $data = (object)[
            'validation_failure' => $validation->fails(),
            'errors' => $validation->errors()->firstOfAll(),
            'code' => 400
        ];

        return response_json($data, $data->code);
    }
    
    $user_id = request()->data->user_id;
    $semester_ids = implode(',', array_column($db->get('semester', [], "is_active = 1")->fetch_all(MYSQLI_ASSOC),'id')) ?? null;
    $ira_ids = implode(',', array_column($db->get('intern_recruitment_application', [], "semester_id in($semester_ids) and status = 1 and user_id = $user_id")->fetch_all(MYSQLI_ASSOC), 'id')) ?? null;
    $list = $db->get('stu_request', [], "status = 1 and ira_id in($ira_ids)");
    $num_rows = $list->num_rows;
    if($num_rows > 0){
        $list = withForArray($list->fetch_all(MYSQLI_ASSOC), [
            'users' => ['foreign_key' => 'user_id', 'primary_key' => 'id', 'model_name' => 'user'],
            'semester' => ['foreign_key' => 'semester_id', 'primary_key' => 'id', 'model_name' => 'semester'],
            'uni_group' => ['foreign_key' => 'group_id', 'primary_key' => 'id', 'model_name' => 'group'],
            'intern_recruitment_application' => ['foreign_key' => 'ira_id', 'primary_key' => 'id', 'model_name' => 'ira']
        ]);
        
        foreach($list as $key => $item){ 
            $list[$key]['user']->fullname = $list[$key]['user']->fname .' '.$list[$key]['user']->lname; 
            $list[$key]['user'] = param_hidden($list[$key]['user'], ['password']);
        }
    }

    $response = (object)[
        'row' => [
            'students' => $list
        ],
        'message' => 'لیست دانشجویان با موفقیت ارسال شذ',
        'code' => 200
    ];

    return response_json($response, $response->code);

}