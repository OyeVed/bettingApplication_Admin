<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $user_id = $_POST['user_id'];
    $deposit_amount = $_POST['deposit_amount'];
    $datetime = date("Y-m-d H:i:s");

    //calculating funds available
    $query = $con->prepare("SELECT amount_in_wallet FROM transaction_details
    WHERE user_id=:user_id ORDER BY transaction_id DESC LIMIT 1");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    if($query->execute()){
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $query->fetch()) {
            $available_amount = $row['amount_in_wallet'];
        }
        $transaction_type = "deposit";
        $transaction_name = $deposit_amount . " rupess added to wallet";
        $amount_in_wallet = $available_amount + $deposit_amount;
        $sql = "INSERT INTO transaction_details ( user_id, transaction_type, transaction_name, transaction_amount, amount_in_wallet, created_at, updated_at) VALUES
        (:user_id, :transaction_type, :transaction_name, :transaction_amount, :amount_in_wallet, :created_at, :updated_at)";
        $query = $con->prepare($sql);
        $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $query->bindParam(':transaction_amount', $deposit_amount, PDO::PARAM_STR);
        $query->bindParam(':amount_in_wallet', $amount_in_wallet, PDO::PARAM_STR);
        $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
        $query->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
        $query->bindparam(":created_at", $datetime, PDO::PARAM_STR);
        $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);
        if($query->execute()){
            $status = 200;
            $response = [
                "msg" => "Transaction placed successfully."
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Transaction can't be placed."
            ];
        }
    }else{
        $status = 203;
        $response = [
            "msg" => "No user transactions found."
        ];
    }

}