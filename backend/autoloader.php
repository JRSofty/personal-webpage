<?php
    if(!defined("CONST_KEY") || CONST_KEY !== "0192443a-e402-7917-b816-53eddacf9003"){
        header("Location: ../403.html", true, 403);
        echo file_get_contents('../403.html');
        die;
    }
    require_once("conf/conf.php");

    $mapping = [
        "api_response"  => "api/api_response.php",
        "api_handler"   => "api/api_handler.php",
        // BUSINESS
        "auth"          => "business/auth.php",
        "version"       => "business/version.php",
        "user"          => "business/user.php",
        "feed"          => "business/feedBusiness.php",
        // DAO
        "abstractDao"   => "persist/abstractDao.php",
        "userDao"       => "persist/userDao.php"
    ];

    spl_autoload_register(function ($class) use ($mapping) {
        if (isset($mapping[$class])) {
            require_once $mapping[$class];
        }
    }, true);

?>