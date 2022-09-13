<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// checking is the user authorized 
if(auth($token)){

    $market_id = $_POST['market_id'];
    $count = 0;

    //fetch details from market
    $sql = "SELECT mr.result_date as date, mt.market_on_days FROM market_results as mr 
    LEFT JOIN market_table as mt
    ON mr.market_id = mt.market_id
    WHERE mr.market_id = :market_id
    ORDER BY mr.result_id DESC LIMIT 1";
    $query = $con -> prepare($sql);
    $query->bindparam(":market_id", $market_id, PDO::PARAM_STR);

    if(!$query->execute()){
        $status = 203;
        $response = [
            "msg" => "Results can't be fetched"
        ];
        return;   
    }
    
    if($query->rowCount() != 0) {

        //taking previous result date and morkaet in days from market table
        $info = $query->fetchAll(PDO::FETCH_ASSOC)[0];
        $date = $info['date'];
        $market_on_days = $info['market_on_days'];
        $market_on_days = explode(",", $market_on_days);

        //finding next available date 
        do{
            $date = date('Y-m-d', strtotime($date . '+1 day'));
            $next_day = date('l', strtotime($date));
          }while(!in_array($next_day, $market_on_days));
        
        $status = 200;
        $response = [
            "msg" => "Result date fetched successfully",
            "date" => $date
        ];


    }else{
        $status = 203;
        $response = [
            "msg" => "No Results Fetched"
        ];
    }
    

}