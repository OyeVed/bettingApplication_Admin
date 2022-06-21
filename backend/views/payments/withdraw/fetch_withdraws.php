<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){
    $withdraws = (object) [];
    try{

        //pending request
        $sql = "SELECT ut.user_fullname, pr.withdrawal_amount, pr.created_at
        FROM pending_request as pr
        LEFT JOIN user_table as ut
        ON pr.user_id = ut.user_id
        ORDER BY pr.request_id desc";
        $query = $con -> prepare($sql);
        if($query->execute()){
            $pending_request = $query->fetchAll(PDO::FETCH_OBJ);

            if($pending_request){
                $withdraws->pending_request = $pending_request;
            }else{
                $status = 203;
                $response = [
                    "msg" => "Pending requests can't be fetched"
                ];
            }
        }


        //withdraw requests
        $transaction_type = "withdrawal";

        $sql = "SELECT td.transaction_id, ut.user_fullname, td.transaction_amount, td.amount_in_wallet, td.created_at
        FROM transaction_details as td
        LEFT JOIN user_table as ut
        ON td.user_id = ut.user_id
        WHERE td.transaction_type =:transaction_type ORDER BY td.transaction_id desc";
        $query = $con -> prepare($sql);
        $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
        if($query->execute()){
            $withdrawal_history = $query->fetchAll(PDO::FETCH_OBJ);

            if($withdrawal_history){
                $withdraws->withdrawal_history = $withdrawal_history;
            }else{
                $status = 203;
                $response = [
                    "msg" => "Withdrawal history can't be fetched"
                ];
            }
            
        }else{

            $status = 203;
            $response = [
                "msg" => "Internal Server Error"
            ];

        }

        // success response
        $status = 200;
        $response = [
            "msg" => "Withdraws fetched successfully",
            "withdraws" => $withdraws
        ];
    }catch(Exception $e){

    }
}