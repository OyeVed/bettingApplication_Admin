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
    $market_id = $_POST['market_id'];
    //fetch details from market
    $sql = "DELETE FROM market_table
    WHERE market_id = :market_id";
    $query = $con -> prepare($sql);
    $query->bindparam("market_id", $market_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Market got deleted."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Market can't be deleted now."
        ];
    }
    

}