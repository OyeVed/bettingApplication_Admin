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
    $datetime = date("Y-m-d H:i:s");

    
    //Checking files specifcation
    //checking extension of file
    $temp = explode('.', $_FILES["choosefile"]["name"]);
    $extn = strtolower(end($temp));
    if(($extn == "jpg") || ($extn == "png") ) {
        // Filetype is correct. Check size
        if($_FILES["choosefile"]["size"] < 2000000) {
            //extracting data from uploaded file
            $filename = $_FILES["choosefile"]["name"];
            $tempname = $_FILES["choosefile"]["tmp_name"];
            $image = base64_encode(file_get_contents($tempname));
        
        
            // query to insert the submitted data
            $sql = "UPDATE admin_user_table SET profile_image = :profile_image,
            updated_at = :updated_at WHERE admin_user_id = :admin_user_id";
            $query = $con -> prepare($sql);
            $query->bindParam(':admin_user_id', $payload->admin_user_id, PDO::PARAM_STR);
            $query->bindParam(':profile_image', $image, PDO::PARAM_LOB);
            $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);
            if($query->execute()){
                $status = 200;
                $response = [
                    "msg" => "Image Uploaded Succesfully"
                ];
            }else{
                $status = 203;
                $response = [
                    "msg" => "Image can't be saved to database"
                ];
            }
        }else{
            $status  = 203;
            $response = [
                "msg" => "Reduce file size to 2 MB."
            ];
        }
    }else{
        $status = 203;
        $response = [
            "msg" => "File is not in specified format."
        ];
    }
}