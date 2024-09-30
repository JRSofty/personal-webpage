<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class version {
        public function getVersion(){
            $res = api_response::getResponse(200);
            $res["version"] = "1.0.0-DEV";
            return $res;
        }
    }


?>