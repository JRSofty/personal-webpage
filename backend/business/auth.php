<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class auth
    {
        private $defaultHeader = ["typ" => "JWT", "alg" => "HS256"];
        
        // private $userDao = new userDao();
        public function loginUser($params){
            $res = api_response::getResponse(400);
            if(!isset($params["post_body"]) || null == $params["post_body"]){
                $res["message"] = "Invalid Login Request (1)";
                return $res;
            }
            $login = $params["post_body"];
            $valRes = $this->validateUser($login);
            if($valRes["status"] != 200){
                return $valRes;
            }
            $user = $valRes["user"];
            $token = $this->createToken($user);
            $res = api_response::getResponse(200);
            setCookie("apiToken", $token, time()+60*60*24);
            return $res;
        }

        private function validateUser($login){
            $res = api_response::getResponse(400);
            if(!isset($login["username"]) || !isset($login["secret"])){
                $res["message"] = "Invalid Login Request (2)";
                return $res;
            }
            $dao = new userDao();
            $res = $dao->getUserByUsername($login["username"]);
            if($res["status"] != 200){
                return $res;
            }
            if(!isset($res["user"])){
                $res = api_response::getResponse(403);
                $res["message"] = "Login failed";
                return $res;
            }
            $dbUser = $res["user"];
            if(!password_verify($login["secret"], $dbUser["secret"])){
                $res = api_response::getResponse(403);
                $res["message"] = "Login failed";
                return $res;
            }
            return $res;

        }

        private function createToken($user){
            // $user = $params["post_body"];
            $header = json_encode($this->defaultHeader);
            $payload = json_encode(["sub" => $user["id"], "name" => $user["username"], "exp" => time()+60*60*24]); // exp = 24h
            $encodedHeader = $this->base64_encode_url($header);
            $encodedPayload = $this->base64_encode_url($payload);
            $signature = $this->createSignature($encodedHeader, $encodedPayload);
            $token = "$encodedHeader.$encodedPayload.$signature";
            return $token;
        }

        private function base64_encode_url($string) {
            return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
        }
        
        private function base64_decode_url($string) {
            return base64_decode(str_replace(['-','_'], ['+','/'], $string));
        }

        private function createSignature($encodedHeader, $encodedPayload){
            $sig = hash_hmac("sha256", $encodedHeader . "." . $encodedPayload, JWT_SECRET, true);
            return $this->base64_encode_url($sig);
        }

        private function hashPass($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }

    }



?>