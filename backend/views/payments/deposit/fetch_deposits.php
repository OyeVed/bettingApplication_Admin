<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $transaction_type = "deposit";

    $sql = "SELECT td.transaction_id, ut.user_fullname, td.transaction_amount, td.amount_in_wallet, td.created_at
    FROM transaction_details as td
    LEFT JOIN user_table as ut
    ON td.user_id = ut.user_id
    WHERE td.transaction_type =:transaction_type ORDER BY td.transaction_id desc";
    $query = $con -> prepare($sql);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    if($query->execute()){
        $deposit_history = $query->fetchAll(PDO::FETCH_OBJ);

        if($deposit_history){
            $status = 200;
            $response = [
                "msg" => "Deposit history fetched successfully",
                "deposit_history" => $deposit_history
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Deposit history can't be fetched"
            ];
        }
        
    }else{

        $status = 203;
        $response = [
            "msg" => "Internal Server Error"
        ];

    }

}