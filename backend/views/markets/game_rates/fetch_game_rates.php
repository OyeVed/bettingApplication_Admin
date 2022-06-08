<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT * FROM game_rate_table ";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $games
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Games can't be fetched"
        ];
    }
    

}