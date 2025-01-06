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
            $user = ["id"=>1, "username" => "testing", "last_name"=>"Dummy", "first_name"=>"Dummy", "email" => "dummy@email.com"];
            $token = $this->createToken($user);
            $res = api_response::getResponse(200);
            setCookie("apiToken", $token, time()+60*60*24);
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

        public function validateToken($token){
            $tokenItems = explode(".", $token);
            $check = $this->createSignature($tokenItems[0], $tokenItems[1]);
            $res = api_response::getResponse(403);
            if($check == $tokenItems[2]){
                $res = api_response::getResponse(200);
            }
            return $res;
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

    }



?>