<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_GET = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){

    $market_id = $_POST['market_id'];
    //fetch details from market
    $sql = "SELECT * FROM market_table WHERE market_id = :market_id";
    $query = $con -> prepare($sql);
    $query->bindparam("market_id", $market_id, PDO::PARAM_STR);
    if($query->execute()){
        $market_details = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $market_details
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}