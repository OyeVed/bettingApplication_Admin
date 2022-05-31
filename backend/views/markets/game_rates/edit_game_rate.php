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
    $rate_id = $_POST['rate_id'];
    //fetch details from query parameter
    $game_rate = $_POST['game_rate'];

    //fetch details from market
    $sql = "UPDATE game_rate_table SET 
    game_rate = :game_rate
    WHERE rate_id = :rate_id";
    $query = $con -> prepare($sql);
    $query->bindparam("game_rate", $game_rate, PDO::PARAM_STR);
    $query->bindparam("rate_id", $rate_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Game rates got updated."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Data can't be updated now."
        ];
    }
    

}