<?php
    header('Access-Control-Allow-Origin: *'); 
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
    header('Content-Type: application/json;charset=utf-8');

    if(!defined("CONST_KEY")){define("CONST_KEY", "0192443a-e402-7917-b816-53eddacf9003");}
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    require_once("autoloader.php");
    
    /*
     * Request Method Meaning
     *  GET == READ
     *  POST == Entity Change (often used as CREATE)
     *  PATCH == UPDATE
     *  PUT == CREATE and REPLACE
     *  DELETE == DELETE
     *  
     */ 
    $requestMethodArray = array();
    $requestMethodArray = $_REQUEST;
    $token = "";
    $adminToken = false;
    $functionParams = "";
    if(isset($requestMethodArray["apiToken"])){$token = $requestMethodArray["apiToken"];}
    if($token == "" && isset($_COOKIE["apiToken"])){
        $token = $_COOKIE["apiToken"];
    }
    if(isset($requestMethodArray["apiFunc"])){ $functionName = $requestMethodArray["apiFunc"];}
    $functionParams = array();
    foreach($requestMethodArray as $key => $value){
        if($key != 'apiToken' && $key != 'apiFunc'){
            $functionParams[$key] = $value;
        }
    }
    $postBody = json_decode(file_get_contents("php://input"), true);
    $functionParams["post_body"] = $postBody;
    $functionParams["files"] = $_FILES;


    $cApiHandler = new api_handler();
    $res = $cApiHandler->callApiFunction($functionName, $functionParams, $requestMethod, $token);
    $returnArray = json_encode($res,JSON_UNESCAPED_UNICODE);
    http_response_code($res["status"]);
    echo $returnArray;
    if(isset($cApiHandler)){unset($cApiHandler);}
?>