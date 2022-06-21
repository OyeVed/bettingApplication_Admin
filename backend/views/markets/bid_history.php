<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from betting_history
    $sql = "SELECT ut.user_fullname, mt.market_fullname, bh.game_name, bh.game_type, bh.number, bh.points, bh.created_at
    FROM betting_history as bh 
    LEFT JOIN market_table as mt ON bh.market_id = mt.market_id 
    LEFT JOIN user_table as ut ON bh.user_id = ut.user_id
    ORDER BY bh.bet_id desc";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $bid_history = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Bets fetched successfully",
            "bid_history" => $bid_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Bets can't be fetched"
        ];
    }
    

}