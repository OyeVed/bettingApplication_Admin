<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){
    //find the market to delete
    $admin_user_id = $_POST['admin_user_id'];
    //fetch details from market
    $sql = "DELETE FROM admin_user_table
    WHERE admin_user_id = :admin_user_id";
    $query = $con -> prepare($sql);
    $query->bindparam("admin_user_id", $admin_user_id, PDO::PARAM_STR);

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