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

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //extracting payload from jwt
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

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

        $admin_profile = $query->fetchAll(PDO::FETCH_OBJ)[0];

        if($admin_profile){
            $status = 200;
            $response = [
                "msg" => "Admin profile fetched successfully",
                "admin_profile" => $admin_profile
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Unauthorized - Password does not match"
            ];
        }

    }
}