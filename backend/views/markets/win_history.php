<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from betting_history
    $sql = "SELECT ut.user_fullname, mt.market_fullname, wh.game_name, wh.win_amount, wh.created_at
    FROM win_history as wh 
    LEFT JOIN market_table as mt ON wh.market_id = mt.market_id 
    LEFT JOIN user_table as ut ON wh.user_id = ut.user_id
    ORDER BY wh.win_id desc";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $win_history = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Win history fetched successfully",
            "win-history" => $win_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Win history can't be fetched"
        ];
    }
    

}