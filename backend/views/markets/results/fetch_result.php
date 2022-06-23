<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from market
    $sql = "SELECT mt.market_fullname, mr.results, mr.created_at FROM market_results as mr
    LEFT JOIN market_table as mt
    ON mr.market_id = mt.market_id
    ORDER BY mr.result_id DESC";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Results fetched successfully",
            "result" => $result
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Results can't be fetched"
        ];
    }
    

}