<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    //fetch details from betting_history
    $sql = "SELECT * FROM betting_history";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $bets = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $bets
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}