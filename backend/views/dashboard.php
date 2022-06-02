<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    $sql = "SELECT * FROM market_table";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $market_data = [];
        $markets = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($markets as $market){
            $sql = "SELECT SUM(points) FROM betting_history WHERE betting_history.market_id = :market_id";
            $query = $con -> prepare($sql);
            $query->bindparam(':market_id', $market->market_id, PDO::PARAM_STR);
            if($query->execute()){
                $total_bid_placed = $query->fetchAll(PDO::FETCH_OBJ);
            }
            if(date($market->market_closetime) > date("H-i-s")){
                $status = "open";
            }else{
                $status = "close";
            }
            $market_details = [
                "market_fullname" => $market->market_fullname,
                "market_opentime" => $market->market_opentime,
                "market_closetime" => $market->market_closetime,
                "market_status" => $status,
                "market_bids" => $total_bid_placed
            ];
            array_push($market_data, $market_details);
        }
        $status = 200;
        $response = [
            "msg" => $market_data
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
}