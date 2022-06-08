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
    //find the market to delete
    $result_id = $_POST['result_id'];
    //fetch details from market
    $sql = "DELETE FROM market_results
    WHERE result_id = :result_id";
    $query = $con -> prepare($sql);
    $query->bindparam("result_id", $result_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Result got deleted."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Result can't be deleted now."
        ];
    }
    

}