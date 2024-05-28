<?php


if(! function_exists("request")){
    function request(){
        $data = [];

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!isset($_REQUEST['method']))
            $_REQUEST['method'] = '';
    

        $data['get'] =(object)$_REQUEST;

        $data['post'] = (object)$_POST;

        $data['data'] = (object)((array)$data['get'] + (array)$data['post']);
        
        return (object)$data;
    }
}

if(!function_exists('env')){
    function env($param = null, $value = null){
        return !isset(parse_ini_file('../.env')[$param]) ? $value : parse_ini_file('../.env')[$param];
    }
}

if(!function_exists('response_json')){
    function response_json($data, $status = 0){
        http_response_code($status);
        print_r(json_encode($data));
    }
}

if(!function_exists('param_hidden')){
    function param_hidden($data, $hideen = []){
        $data = (array) $data;

        foreach($hideen as $i){
            unset($data[$i]);
        }

        return (object) $data;
    }
}