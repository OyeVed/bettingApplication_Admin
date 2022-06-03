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
    //find the market to edit
    $user_id = $_POST['user_id'];

    //fetch details from query parameter
    $phone_number = $_POST['phone_number'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    //fetch details from market
    $sql = "UPDATE user_table SET 
    user_phonenumber=:user_phonenumber, 
    user_password=:user_password, 
    user_email=:user_email, 
    user_fullname=:user_fullname
    WHERE user_id = :user_id ";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':user_password', $password, PDO::PARAM_STR);
    $query->bindParam(':user_email', $email, PDO::PARAM_STR);
    $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "User data got updated."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Data can't be updated now."
        ];
    }
    

}