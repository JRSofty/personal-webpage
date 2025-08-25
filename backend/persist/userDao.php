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

        public function listAllUsers(){
            $query = "SELECT `id`, `username`, `last_name`, `first_name`, `email` FROM users";
            $statement = $this->prepareStatement($query);
            $result = api_response::getResponse(404);
            try{
                $statement->execute();
                $users = $statement->fetchAll(PDO::FETCH_ASSOC);
                $result = api_response::getResponse(200);
                $result["users"] = $users;
            }catch(Exception $e){
                $result = api_response::getResponse(500);
                $result["exception"] = $e->getMessage();
            }finally{
                $this->disconnect();
            }
            return $result;

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

        public function getUserById($uid){
            $query = "SELECT `id`, `username`, `last_name`, `first_name`, `email` FROM `users` WHERE `id` = :uid";
            $params = ["uid" => $uid];
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

        public function deleteUserById($uid){
            $query = "DELETE FROM `users` WHERE `id` = :uid";
            $params = ["uid" => $uid];
            $statement = $this->prepareStatement($query);
            $result = api_response::getResponse(200);
            try{
                $statement->execute($params);
            }catch(Exception $e){
                $result = api_response::getResponse(500);
                $result["exception"] = $e->getMessage();
            }finally{
                $this->disconnect();
            }
            return $result;
        }

        public function createUser($user){
            $query = "INSERT INTO `users` (`username`, `last_name`, `first_name`, `secret`, `email`) VALUES (:username, :last_name, :first_name, :secret, :email)";
            $statement = $this->prepareStatement($query);
            $result = api_response::getResponse(200);
            try{
                $statement->execute($user);
            }catch(Exception $e){
                $result = api_response::getResponse(500);
                $result["exception"] = $e->getMessage();
            }
            finally{
                $this->disconnect();
            }
            return $result;
        }

        public function updateUser($user){
            $setValues = "";
            foreach($user as $key=>$value){
                if($key != "id"){
                    $setValues .= "`$key` = :$key";
                    if($key != array_key_last($user)){
                        $setValues .= ", ";
                    }
                }
            }
            $query = "UPDATE `users` SET $setValues WHERE `id` = :id";
            $statement = $this->prepareStatement($query);
            $result = api_response::getResponse(200);
            try{
                $statement->execute($user);
            }catch(Exception $e){
                $result = api_response::getResponse(500);
                $result["exception"] = $e->getMessage();
            }finally{
                $this->disconnect();
            }

            return $result;

        }
    }

?>