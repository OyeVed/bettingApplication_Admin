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
    $rate_id = $_POST['rate_id'];
    $game_rate = $_POST['game_rate'];
    $datetime = date("Y-m-d H:i:s");
    $reward_factor = $game_rate/10;

    //fetch details from market
    $sql = "UPDATE game_rate_table SET 
    game_rate = :game_rate,
    reward_factor = :reward_factor,
    updated_at = :updated_at
    WHERE rate_id = :rate_id";
    $query = $con -> prepare($sql);
    $query->bindparam("game_rate", $game_rate, PDO::PARAM_STR);
    $query->bindparam("reward_factor", $reward_factor, PDO::PARAM_STR);
    $query->bindparam("rate_id", $rate_id, PDO::PARAM_STR);
    $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Game rate edited successfully."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Game rate can't be edited."
        ];
    }
    

}