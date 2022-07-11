<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    // $curr_date = date("Y-m-d");
    $curr_day = date('l');
    $sql = "SELECT mt.market_id, mt.market_fullname, mt.market_on_days, mt.market_opentime, mt.market_closetime, mr.results
    FROM market_table as mt
    LEFT JOIN market_results as mr
    ON mt.market_id = mr.market_id";
    /* WHERE mr.created_at = :curr_date > */
    $query = $con -> prepare($sql);
    if($query->execute()){
        $market_tile = [];
        $total_live_games = 0;
        $total_closed_games = 0;
        $markets = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($markets as $market){
            $market_on_days = $market->market_on_days;
            $market_on_days = explode(",", $market_on_days);
            if((date($market->market_opentime) < date("H-i-s")) && (date($market->market_closetime) >= date("H-i-s")) && (in_array($curr_day, $market_on_days))){
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
                "market_result" => $market->results,
                "market_status" => $status
            ];
            array_push($market_tile, $market_details);
        }

        $status = 200;
        $response = [
            "msg" => "Market data fetched successfully",
            "market_tile" => $market_tile,
            "total_live_games" => $total_live_games,
            "total_closed_games" => $total_closed_games
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Marekt data can't be fetched"
        ];
    }
    

}