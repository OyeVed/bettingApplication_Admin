<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from query parameter
    $market_id = $_POST['market_id'];
    $results = $_POST['results'];

    //fetch details from market
    $sql = "INSERT INTO market_results (market_id, results) 
    VALUES (:market_id, :results)";
    $query = $con -> prepare($sql);
    $query->bindparam("market_id", $market_id, PDO::PARAM_STR);
    $query->bindparam("results", $results, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Result data got updated."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Result can't be added now."
        ];
    }
    

}