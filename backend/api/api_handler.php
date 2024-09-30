<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class api_handler {
        private $function_map;

        public function __construct(){
            $this->loadFunctionMap();
        }

        public function callApiFunction($apiFunction, $apiParams, $requestMethod, $adminToken){
            $res = $this->getCommand($apiFunction);
            
            if($res['success'] === true){
                $needToken = $res["dataArray"]->getToken();
                $class = $res["dataArray"]->getClazz();
                $func = $res["dataArray"]->getFunc();
                $method = $res["dataArray"]->getMethod();
                
                if(strcmp($method, $requestMethod) !== 0){
                    $res = api_response::getResponse(405);
                    return $res;
                }

                // if($needToken == true){
                //     if( $token == ""){
                //         $res = api_response::getResponse(400);
                //         $res["message"] = "apiToken failure likely in cookies.";
                //         $res["cookie"] = $_COOKIE;
                //         $res["request"] = $requestMethodArray;
                //         $res["params"] = $functionParams;
                //     }else{
                //         $res = api_token::validate($token, $adminToken);
                //     }
                // }
                // if($res["status"] !== 200){
                //     return res;
                // }

                $cCommand = new $class();
                $res = $cCommand->$func($apiParams);
            }
            return $res;
        }

        private function getCommand($apiFunction){
            if(isset($this->function_map[$apiFunction])){
                $res = api_response::getResponse(200);
                $res["dataArray"] = $this->function_map[$apiFunction];
                return $res;
            }else{
                $res = api_response::getResponse(405);
                return $res;
            }
        }


        private function loadFunctionMap(){
            $this->function_map = [
                "version" => new api_registry("version", "getVersion", false, "GET")
            ];
        }



    }

    class api_registry
    {
        private $clazz = '';
        private $func = '';
        private $token = false;
        private $method = '';

        public function __construct($clazz, $func, $token, $method){
            $this->clazz = $clazz;
            $this->func = $func;
            $this->token = $token;
            $this->method = $method;
        }

        public function getClazz(){
            return $this->clazz;
        }

        public function getFunc(){
            return $this->func;
        }

        public function getToken(){
            return $this->token;
        }

        public function getMethod(){
            return $this->method;
        }

    }


?>