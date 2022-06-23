<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT game_name, game_rate FROM game_rate_table ";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $game_rate = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "",
            "game_rate" => $game_rate
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Games rate can't be fetched"
        ];
    }
    

}