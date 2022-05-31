<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    //find the market to delete
    $user_id = $_POST['user_id'];
    //fetch details from market
    $sql = "DELETE FROM user_table
    WHERE user_id = 1";
    $query = $con -> prepare($sql);
    // $query->bindparam("user_id", $user_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "User got deleted."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "User can't be deleted now."
        ];
    }
    

}