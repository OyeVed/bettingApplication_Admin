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
    $game_name = $_POST['game_name'];
    $game_rate = $_POST['game_rate'];

    //fetch details from market
    $sql = "INSERT INTO game_rate_table (game_name, game_rate) 
    VALUES (:game_name, :game_rate)";
    $query = $con -> prepare($sql);
    $query->bindparam("game_name", $game_name, PDO::PARAM_STR);
    $query->bindparam("game_rate", $game_rate, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Game data got added."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Game can't be added now."
        ];
    }
    

}