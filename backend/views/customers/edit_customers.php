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

    //extracting payload from jwt
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    //fetch details from query parameter
    $user_id = $_POST['user_id'];
    $phone_number = $_POST['phone_number'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $withdrawal_method = $_POST['withdrawal_method'];
    $upi_id = $_POST['upi_id'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];
    $datetime = date("Y-m-d H:i:s");

    //fetch details from market
    $sql = "UPDATE user_table SET 
    user_phonenumber=:user_phonenumber,
    user_email=:user_email, 
    user_fullname=:user_fullname,
    user_password=:user_password, 
    withdrawal_method=:withdrawal_method,
    upi_id=:upi_id,
    bank_name=:bank_name,
    account_number=:account_number,
    ifsc_code=:ifsc_code,
    updated_at = :updated_at
    WHERE user_id = :user_id ";
    
    $query = $con -> prepare($sql);
    $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':user_email', $email, PDO::PARAM_STR);
    $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);
    $query->bindParam(':user_password', $password, PDO::PARAM_STR);
    $query->bindParam(':withdrawal_method', $withdrawal_method, PDO::PARAM_STR);
    $query->bindParam(':upi_id', $upi_id, PDO::PARAM_STR);
    $query->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
    $query->bindParam(':account_number', $account_number, PDO::PARAM_STR);
    $query->bindParam(':ifsc_code', $ifsc_code, PDO::PARAM_STR);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);

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