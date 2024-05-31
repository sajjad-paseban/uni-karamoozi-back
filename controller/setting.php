<?php 
require_once '../helper/function.php';
require_once "../helper/includes.php";

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


// if(request()->method == "get-data"){
    
// }


// if(request()->method == "get-data"){
    
// }

// if(request()->method == "get-data"){
    
// }

// if(request()->method == "get-data"){
    
// }

// if(request()->method == "get-data"){
    
// }
