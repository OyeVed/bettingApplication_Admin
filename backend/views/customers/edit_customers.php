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
    ifsc_code=:ifsc_code
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