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

    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    // retrieve required variables
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    
    
    $sql = "SELECT * FROM admin_user_table WHERE admin_user_table.admin_user_id=:admin_user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() != 0){

        $sql = "UPDATE admin_user_table SET 
            admin_phonenumber=:user_phonenumber,
            admin_email_id=:user_email, 
            admin_fullname=:user_fullname
            WHERE admin_user_id=:admin_user_id";
        $query = $con -> prepare($sql);
        $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
        $query->bindParam(':user_email', $email, PDO::PARAM_STR);
        $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);
        $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);
        if($query->execute()){
            $user = $query->fetchAll(PDO::FETCH_OBJ);
            $status = 200;
            $response = [
                "msg" => "Admin data updated successfully",
                "admin" => [
                    "phone_number" => $phone_number,
                    "full_name" => $full_name,
                    "email" => $email
                ]
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Internal Server Error - User data can't be updated"
            ];
        }

    }else{

        $status = 203;
        $response = [
            "msg" => "Bad Request - Incorrect username."
        ];

    }
}