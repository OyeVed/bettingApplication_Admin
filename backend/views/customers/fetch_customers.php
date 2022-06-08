<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT user_id, user_fullname, user_email, user_phonenumber, withdrawal_method, upi_id, bank_name, account_number, ifsc_code FROM user_table ORDER BY user_id DESC";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $users = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $users
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}