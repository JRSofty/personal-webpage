<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class api_response{
        public static function getResponse($statusCode){
            $response["success"] = '';
            $response["status"] = '';
            $response["message"] = '';

            switch($statusCode){
                case 200:
                    $response["success"] = true;
                    $response["status"] = 200;
                    $response["message"] = "Operation successful";
                    break;
                case 302:
                    $response["success"] = true;
                    $resposne["status"] = 302;
                    $response["message"] = "Already Exists";
                case 400:
                    $response["success"] = false;
                    $response["status"] = 400;
                    $response["message"] = "Bad Request, expected fields are missing";
                    break;
                case 403:
                    $response["success"] = false;
                    $response["status"] = 403;
                    $response["message"] = "Invalid Token Access Not Allowed.";
                    break;
                case 405:
                    $response["success"] = false;
                    $response["status"] = 405;
                    $response["message"] = "Method not allowed. The method specified in the Request-Line is not allowed for the resource identified by the Request-URI.";
                    break;
                case 500:
                    $response["success"] = false;
                    $response["status"] = 500;
                    $response["message"] = "Server failure";
                    break;
                default:
                    $response["success"] = false;
                    $response["status"] = 000;
                    $response["message"] ="Unknown application operation.";
                    $response["code"] = $statusCode;
            }
            return $response;
        }
    }


?>