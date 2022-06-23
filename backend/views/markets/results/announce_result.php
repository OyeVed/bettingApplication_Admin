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
    $market_id = $_POST['market_id'];
    $result = $_POST['result'];

    function get_results($result){
        $results = [];

        

        return $results;
    }


    $result_array = get_results($result);
    echo $a;
}


















// $curr_date = date("Y-m-d");
// $sql = "SELECT created_at FROM betting_history as a WHERE user_id=:user_id AND CAST(created_at AS DATE) = :curr_date";
// $query = $con -> prepare($sql);
// $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
// $query->bindParam(':curr_date', $curr_date, PDO::PARAM_STR);
// $query->execute();
// $created_at = $query->fetchAll(PDO::FETCH_ASSOC);