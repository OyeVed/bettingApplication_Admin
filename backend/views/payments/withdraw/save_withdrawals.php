<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $datetime = date("Y-m-d H:i:s");

    if($status == "accept"){
        $query = $con->prepare("SELECT user_id, withdrawal_amount FROM pending_request
        WHERE request_id=:request_id");
        $query->bindParam(':request_id', $request_id, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() != 0){
            $pending_request_details = $query->fetchAll(PDO::FETCH_ASSOC)[0];
            $user_id = $pending_request_details['user_id'];
            $withdrawal_amount = $pending_request_details['withdrawal_amount'];
            //calculating funds available
            $query = $con->prepare("SELECT amount_in_wallet FROM transaction_details
            WHERE user_id=:user_id ORDER BY transaction_id DESC LIMIT 1");
            $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            if($query->execute()){
                $query->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $query->fetch()) {
                    $available_amount = $row['amount_in_wallet'];
                }
                if($available_amount >= $withdrawal_amount){
                    $transaction_type = "withdrawal";
                    $transaction_name = "Withdrawn from wallet";
                    $amount_in_wallet = $available_amount - $withdrawal_amount;
                    $sql = "INSERT INTO transaction_details ( user_id, transaction_type, transaction_name, transaction_amount, amount_in_wallet, created_at, updated_at) VALUES
                    (:user_id, :transaction_type, :transaction_name, :transaction_amount, :amount_in_wallet, :created_at, :updated_at)";
                    $query = $con->prepare($sql);
                    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                    $query->bindParam(':transaction_amount', $withdrawal_amount, PDO::PARAM_STR);
                    $query->bindParam(':amount_in_wallet', $amount_in_wallet, PDO::PARAM_STR);
                    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
                    $query->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
                    $query->bindparam(":created_at", $datetime, PDO::PARAM_STR);
                    $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);
                    if($query->execute()){
                        $sql = "DELETE FROM pending_request WHERE 
                        request_id = :request_id AND user_id = :user_id AND withdrawal_amount = :withdrawal_amount";
                        $query = $con -> prepare($sql);
                        $query->bindParam(':request_id', $request_id, PDO::PARAM_STR);
                        $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                        $query->bindParam(':withdrawal_amount', $withdrawal_amount, PDO::PARAM_STR);
                        if($query->execute()){
                            $status = 200;
                            $response = [
                                "msg" => "Transaction placed successfully."
                            ];
                        }else{
                            $status = 203;
                            $response = [
                                "msg" => "Request not removed from pending requests."
                            ];
                        }
                        
                    }else{
                        $status = 203;
                        $response = [
                            "msg" => "Transaction can't be placed."
                        ];
                    }
                }else{
                    $status = 203;
                    $response = [
                        "msg" => "Your wallet balance is low for this transaction",
                        "wallet_balance" => $available_amount
                    ];
                }
    
            }else{
                $status = 203;
                $response = [
                    "msg" => "No user transactions found."
                ];
            }
    
        }else{
            $status = 203;
            $response = [
                "msg" => "Can't fetch user's pending request."
            ];       
        }
    }else{
        $sql = "DELETE FROM pending_request WHERE request_id = :request_id";
        $query = $con -> prepare($sql);
        $query->bindParam(':request_id', $request_id, PDO::PARAM_STR);
        if($query->execute()){
            $status = 200;
            $response = [
                "msg" => "Transaction discarded."
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Can't delete transaction from pending request."
            ];
        }
    }

}