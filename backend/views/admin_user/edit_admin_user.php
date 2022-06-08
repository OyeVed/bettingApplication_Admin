<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];
// checking is the user authorized 
if(auth($token)){
    //extracting payload from jwt
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    //fetch details from query parameter
    $phone_number = $_POST['phone_number'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    //fetch details from market
    $sql = "UPDATE admin_user_table SET 
        admin_phonenumber=:admin_phonenumber,
        admin_email_id=:admin_email_id, 
        admin_fullname=:admin_fullname
    WHERE admin_user_id = :admin_user_id ";
    $query = $con -> prepare($sql);
    $query->bindParam(':admin_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':admin_email_id', $email, PDO::PARAM_STR);
    $query->bindParam(':admin_fullname', $full_name, PDO::PARAM_STR);
    $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);

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