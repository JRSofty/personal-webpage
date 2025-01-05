<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class auth
    {
        private $defaultHeader = ["typ" => "JWT", "alg" => "HS256"];
        

        public function createToken($params){
            $user = $params["post_body"];
            $header = json_encode($this->defaultHeader);
            $payload = json_encode(["sub" => $user["id"], "name" => $user["name"], "exp" => time()+60*60*24]); // exp = 24h
            $encodedHeader = $this->base64_encode_url($header);
            $encodedPayload = $this->base64_encode_url($payload);
            $signature = $this->createSignature($encodedHeader, $encodedPayload);
            $token = "$encodedHeader.$encodedPayload.$signature";
            $res = api_response::getResponse(200);
            $res["token"] = $token;
            return $res;
        }

        public function validateToken($token){

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