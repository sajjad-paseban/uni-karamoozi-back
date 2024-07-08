<?php

require_once "../helper/includes.php";

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

if(!function_exists('customErrorMessage')){
    function customErrorMessage($field, $error){
        $erros = [
            'required' => "فیلد $field اجباری می باشد",
            'email' => "$field نامعتبر می باشد",
        ];

        return $erros[$error];
    }
}

if(!function_exists('withForArray')){
    function withForArray($target, $models){

        $db = new DB();
        foreach($target as $key1 => $item){
            foreach($models as $key2 => $model){
                if(is_int($key2)){
                    
                    $foreign_key = ((string)$model) . '_id';
                    $foreign_key = $item[$foreign_key];
                    $data = $db->get((string)$model, [], "id = $foreign_key");
                    $res = ($data->num_rows > 1) ? $data->fetch_all(MYSQLI_ASSOC) : $data->fetch_object();

                    $target[$key1][$model] = $res ?? null;
                }else{
                    $model = (object) $model;
                    $primary_key = (string) $model->primary_key;
                    $foreign_key = (string) $model->foreign_key;
                    $foreign_key = $item[$foreign_key];
                    $data = $db->get((string)$key2, [], "$primary_key = $foreign_key");
                    $res = ($data->num_rows > 1) ? $data->fetch_all(MYSQLI_ASSOC) : $data->fetch_object();
                    $target[$key1][(string) $model->model_name] = $res ?? null;
                }
            }
        }

        return $target;

    }
}

if(!function_exists('withForObject')){
    function withForObject($target, $models){

        $db = new DB();
        foreach($models as $key2 => $model){
            if(is_int($key2)){
                
                $foreign_key = ((string)$model) . '_id';
                $foreign_key = $target->$foreign_key;
                $data = $db->get((string)$model, [], "id = $foreign_key");
                $res = ($data->num_rows > 1) ? $data->fetch_all(MYSQLI_ASSOC) : $data->fetch_object();
                $target = (array) $target;
                $target[$model] = $res ?? null;
            }else{
                $target = (object) $target;
                $model = (object) $model;
                $primary_key = (string) $model->primary_key;
                $foreign_key = (string) $model->foreign_key;
                $foreign_key = $target->$foreign_key;
                $data = $db->get((string)$key2, [], "$primary_key = $foreign_key");
                $res = ($data->num_rows > 1) ? $data->fetch_all(MYSQLI_ASSOC) : $data->fetch_object();
                
                $target = (array) $target;
                $target[$model->model_name] = $res ?? null;
            }
        }

        return (object)$target;

    }
}