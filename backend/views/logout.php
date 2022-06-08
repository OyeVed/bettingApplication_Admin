<?php

require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){
    setcookie("admin_jwt", $token, time()-3600);    

    $status = 200;
    
    $response = [
        "msg" => "Logged out successfully"
    ];
}

