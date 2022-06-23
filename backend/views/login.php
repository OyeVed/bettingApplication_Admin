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

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$phone_number = $_POST['phone_number'];
$password = md5($_POST['password']);

// looking for the user in database
$sql = "SELECT * FROM admin_user_table WHERE admin_user_table.admin_phonenumber=:phone_number";
$query = $con -> prepare($sql);
$query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    $status = 203;
    $response = [
        "msg" => "Bad Request - User does not exist"
    ];

}else{

    // fetching row of that user
    $admin = $query->fetchAll(PDO::FETCH_OBJ)[0];

    if($admin->admin_password === $password){

        // if admin is authenticated then generate a token with JWT
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+30 days')->getTimestamp();      // Add 30 days
        $serverName = "http://localhost/matkaApplicaton/backend/login";     // Retrieved from filtered POST data

        $data = [
            'iat'  => $issuedAt->getTimestamp(),                 // Issued at: time when the token was generated
            'iss'  => $serverName,                               // Issuer
            'nbf'  => $issuedAt->getTimestamp(),                 // Not before
            'exp'  => $expire,                                   // Expire
            'admin_phonenumber' => $admin->admin_phonenumber,    //admin name
            'admin_user_id' =>  $admin->admin_user_id                 // admin id           
        ];

        $jwt =  JWT::encode(
            $data,
            $SECRET_KEY,
            'HS512'
        );
        // sending jwt token to frontend with cookies
        setcookie("admin_jwt", $jwt, time()+ (86400 * 30), "/","", 0); //86400*7 expiry time to 7 days

        $status = 200;
        $response = [
            "msg" => "User authenticated successfully"
        ];

    }else{
        $status = 203;
        $response = [
            "msg" => "Unauthorized - Password does not match"
        ];
    }

}