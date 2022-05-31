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
    //find the market to edit
    $market_id = $_POST['market_id'];
    //fetch details from query parameter
    $market_fullname = $_POST['market_fullname'];
    $market_opentime = $_POST['market_opentime'];
    $market_closetime = $_POST['market_closetime'];

    //fetch details from market
    $sql = "UPDATE market_table SET 
    market_fullname = :market_fullname,
    market_opentime = :market_opentime,
    market_closetime = :market_closetime
    WHERE market_id = :market_id";
    $query = $con -> prepare($sql);
    $query->bindparam("market_fullname", $market_fullname, PDO::PARAM_STR);
    $query->bindparam("market_opentime", $market_opentime, PDO::PARAM_STR);
    $query->bindparam("market_closetime", $market_closetime, PDO::PARAM_STR);
    $query->bindparam("market_id", $market_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Market data got updated."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Data can't be updated now."
        ];
    }
    

}