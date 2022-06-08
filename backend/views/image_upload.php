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

    //extracting data from uploaded file
    $filename = $_FILES["choosefile"]["name"];
    $tempname = $_FILES["choosefile"]["tmp_name"];  
    $folder = "images/".$filename;
    $image = base64_encode(file_get_contents($tempname));


    // query to insert the submitted data
    $sql = "INSERT INTO image_upload (user_id, profile_image) VALUES (:user_id, :profile_image)";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $payload->admin_user_id, PDO::PARAM_STR);
    $query->bindParam(':profile_image', $image, PDO::PARAM_STR);
    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Image Uploaded Succesfully"
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Data can't be saved to database"
        ];
    }
}

