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

                if($needToken == true){
                    $res = $this->validateToken($adminToken);
                    if($res["status"] !== 200){
                        // $res["cookies"] = $_COOKIE;
                        return $res;
                    }
                }

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
                "auth"      => new api_registry("auth",     "loginUser",    false,  "POST"  ),
                "version"   => new api_registry("version",  "getVersion",   false,  "GET"   ),
                "newUser"   => new api_registry("user",     "createUser",   true,   "POST"  ),
                "editUser"  => new api_registry("user",     "updateUser",   true,   "POST"  ),
                "remUser"   => new api_registry("user",     "deleteUser",   true,   "GET"   ),
                "listUsers" => new api_registry("user",     "listUsers",    true,   "GET"   ),
                "getUser"   => new api_registry("user",     "getUser",      false,  "GET"   ),
                "saveArt"   => new api_registry("article",  "saveArticle",  true,   "POST"  ),
                "delArt"    => new api_registry("article",  "deleteArticle",true,   "GET"   ),
                "getArt"    => new api_registry("article",  "getArticle",   false,  "GET"   ),
                "listArt"   => new api_registry("article",  "listArticle",  false,  "GET"   ),
                "newTag"    => new api_registry("tag",      "createTag",    true,   "POST"  ),
                "viewTag"   => new api_registry("article",  "listByTag",    false,  "GET"   ),
                "delTag"    => new api_registry("tag",      "deleteTag",    true,   "GET"   ),
                "editTag"   => new api_registry("tag",      "editTag",      true,   "POST"  )
            ];
        }

        private function validateToken($token){
            $res = api_response::getResponse(403);
            if(!isset($token) || $token == ""){
                $res["messsage"] = "Invalid Token";
                return $res;
            }
            $tokenItems = explode(".", $token);
            $check = $this->createSignature($tokenItems[0], $tokenItems[1]);
            if($check == $tokenItems[2]){
                $res = api_response::getResponse(200);
            }else{
                $res["message"] = "Token Validation Failed";
                $res["token2"] = $tokenItems[2];
                $res["check"] = $check;
            }
            return $res;
        }

        private function base64_encode_url($string) {
            return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
        }

        private function createSignature($encodedHeader, $encodedPayload){
            $sig = hash_hmac("sha256", $encodedHeader . "." . $encodedPayload, JWT_SECRET, true);
            return $this->base64_encode_url($sig);
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