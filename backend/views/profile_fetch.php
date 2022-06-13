<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //extracting payload from jwt
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    $sql = "SELECT * FROM admin_user_table WHERE admin_user_id=:admin_user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() === 0){

        $status = 203;
        $response = [
            "msg" => "Bad Request - User does not exist"
        ];

    }else{

        $admin = $query->fetchAll(PDO::FETCH_OBJ)[0];

        if($admin){
            $status = 200;
            $response = [
                "msg" => $admin
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Unauthorized - Password does not match"
            ];
        }
        // echo '<pre>';
        // print_r($admin);

    }
}