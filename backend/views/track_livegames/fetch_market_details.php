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

    $market_id = $_POST['market_id'];
    //fetch details from market
    $sql = "SELECT bh.user_id, bh.market_id, ut.user_fullname, mt.market_fullname, bh.game_name, bh.game_type, bh.number, bh.points, bh.created_at
    FROM betting_history as bh 
    LEFT JOIN user_table as ut
    ON ut.user_id = bh.user_id
    LEFT JOIN market_table as mt
    ON mt.market_id = bh.market_id
    WHERE bh.market_id = :market_id";
    $query = $con -> prepare($sql);
    $query->bindparam("market_id", $market_id, PDO::PARAM_STR);
    if($query->execute()){
        $market_details = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Market details fetched successfully",
            "market_details" => $market_details
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}