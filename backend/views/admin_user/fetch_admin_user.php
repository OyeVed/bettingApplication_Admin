<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT admin_user_id, admin_fullname, admin_email_id, admin_phonenumber FROM admin_user_table ORDER BY admin_user_id DESC";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $admin_users = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $admin_users
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}