<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT * FROM market_table ";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $market_tile = [];
        $total_live_games = 0;
        $total_closed_games = 0;
        $markets = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($markets as $market){
            if(date($market->market_closetime) > date("H-i-s")){
                $status = "open";
                $total_live_games += 1;
            }else{
                $status = "close";
                $total_closed_games += 1;
            }
            $market_details = [
                "market_id" => $market->market_id,
                "market_fullname" => $market->market_fullname,
                "market_opentime" => $market->market_opentime,
                "market_closetime" => $market->market_closetime,
                "market_status" => $status
            ];
            array_push($market_tile, $market_details);
        }

        $status = 200;
        $response = [
            "msg" => $market_tile,
            "total_live_games" => $total_live_games,
            "total_closed_games" => $total_closed_games
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}