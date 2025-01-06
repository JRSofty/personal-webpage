<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }


    class userDao extends abstractDao
    {
        public function __construct(){
            $this->connect();
        }

        public function getUserByUsername($username){    
            $query = "SELECT `id`, `username`, `secret`, `last_name`, `first_name`, `email` FROM `users` WHERE `username` = :username";
            $params = ["username" => $username];
            $statement = $this->prepareStatement($query);
            $result = api_response::getResponse(404);
            try{
                $statement->execute($params);
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                if(is_array($row)){
                    $result = api_response::getResponse(200);
                    $result["user"] = $row;
                }
            }catch(Exception $e){
                $result = api_response::getResponse(500);
                $result["exception"] = $e->getMessage();
            }finally{
                $this->disconnect();
            }
            if($result["status"] == 404){
                $result["message"] = "No User Found";
            }
            return $result;
        }
    }

?>