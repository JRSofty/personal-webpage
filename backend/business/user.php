<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    class user {

        public function listUsers(){
            $dao = new userDao();
            $res = $dao->listAllUsers();
            return $res;
        }

        public function getUser($params){
            $res = api_response::getResponse(400);
            if(!isset($params["uid"])){
                $res["message"] = "Missing parameter 'uid'";
                return $res;
            }
            $dao = new userDao();
            $res = $dao->getUserById($params["uid"]);
            return $res;
        }

        public function createUser($params){
            $res = api_response::getResponse(400);
            if(!isset($params["post_body"])){
                $res["messsage"] = "No content";
                return $res;
            }
            $user = $params["post_body"];
            try{
                $user = $this->validateUserObject($user);
            }catch(Exception $e){
                $res["message"] = $e->getMessage();
                return $res;
            }
            $dao = new userDao();
            $res = $dao->createUser($user);
            return $res;
        }

        public function updateUser($params){
            $res = api_response::getResponse(400);
            if(!isset($params["post_body"])){
                $res["messsage"] = "No content";
                return $res;
            }
            $user = $params["post_body"];
            try{
              $user = $this->validateUserObject($user, true);
            }catch(Exception $e){
                $res["message"] = $e->getMessage();
                return $res;
            }
            $dao = new userDao();
            $res = $dao->updateUser($user);
            return $res;
        }

        public function deleteUser($params){
            $res = api_response::getResponse(400);
            if(!isset($params["uid"])){
                $res["message"] = "MIssing parameter 'uid'";
                return $res;
            }
            $dao = new userDao();
            $res = $dao->deleteUserById($params["uid"]);
            return $res;
        }

        private function validateUserObject($user, $update = false){
            if($update && !isset($user["id"])){
                throw new Exception("id field missing");
            }
            if(!$update && isset($user["id"])){
                throw new Exception("id field should not be included");
            }
            if(!isset($user["username"])){
                throw new Exception("username field is missing");
            }
            if(!isset($user["last_name"])){
                throw new Exception("last_name field is missing");
            }
            if(!isset($user["first_name"])){
                throw new Exception("first_name field is missing");
            }
            if(!isset($user["email"])){
                throw new Exception("email field is missing");
            }

            if(!$update && !isset($user["secret"])){
                throw new Exception("secret field is missing");
            }
            if(isset($user["secret"])){
                $user["secret"] = $this->hashPass($user["secret"]);
            }
            return $user;
        }

        private function hashPass($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }

    }
    
?>