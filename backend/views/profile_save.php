<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Dotenv\Dotenv;

// Looing for .env at the root directory
$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    // retrieve required variables
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $datetime = date("Y-m-d H:i:s");
    
    
    $sql = "UPDATE admin_user_table SET 
    admin_phonenumber=:user_phonenumber,
    admin_email_id=:user_email, 
    admin_fullname=:user_fullname,
    updated_at = :updated_at
    WHERE admin_user_id=:admin_user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':user_email', $email, PDO::PARAM_STR);
    $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);
    $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);
    $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);
    if($query->execute()){
        $user = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Admin data updated successfully"
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal Server Error - User data can't be updated"
        ];
    }
}