<?php

if(! function_exists("request")){
    function request(){
        $data = [];
        if(!isset($_REQUEST['method']))
            $_REQUEST['method'] = '';
    

        $data['get'] =(object)$_REQUEST;

        $data['post'] = json_decode(file_get_contents('php://input'));

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