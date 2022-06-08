<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $transaction_type = "won";
    //fetch details from betting_history
    $sql = "SELECT * FROM transaction_details WHERE transaction_details.transaction_type = :transaction_type";
    $query = $con -> prepare($sql);
    $query->bindparam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    if($query->execute()){
        $win_history = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $win_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "data can't be fetched"
        ];
    }
    

}