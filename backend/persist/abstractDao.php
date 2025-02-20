<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../../403.html", true, 403);
        echo file_get_contents('../../403.html');
        die;
    }

    abstract class abstractDao 
    {
        protected $pdo;

        protected function connect(){
            $dns = "mysql:host=" . CONST_DB_SERV . ";dbname=" . CONST_DB_NAME .";charset=" . CONST_DB_CHAR;
            try{
                $this->pdo = new PDO($dns, CONST_DB_USER, CONST_DB_PASS);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo "ERROR!: " . $e->getMessage() . "<br>";
                die();
            }
        }

        protected function prepareStatement($query){
            return $this->pdo->prepare($query);
        }

        protected function getPdo(){
            return $this->pdo;
        }

        protected function disconnect(){
            unset($this->pdo);
        }
    }

?>