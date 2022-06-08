<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){
    //find the market to edit
    $result_id = $_POST['result_id'];
    //fetch details from query parameter
    $results = $_POST['results'];

    //fetch details from market
    $sql = "UPDATE market_results SET 
    results = :results
    WHERE result_id = :result_id";
    $query = $con -> prepare($sql);
    $query->bindparam("result_id", $result_id, PDO::PARAM_STR);
    $query->bindparam("results", $results, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Results data got updated."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Data can't be updated now."
        ];
    }
    

}