<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    //todays_winnings
    $sql = "SELECT SUM(win_amount) as wa FROM win_history 
    WHERE DATE(created_at) = CURDATE()";
    $query = $con -> prepare($sql);
    if($query->execute()){
        $todays_winnings = $query->fetchAll(PDO::FETCH_ASSOC)[0]['wa'];
        $status = 200;
        $response = [
            "msg" => $todays_winnings
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Can't fetch users."
        ];
    }
}





    // // fetch markets and bids placed in each market
    // $sql = "SELECT * FROM market_table";
    // $query = $con -> prepare($sql);
    // if($query->execute()){
    //     $market_data = [];
    //     $markets = $query->fetchAll(PDO::FETCH_OBJ);
    //     foreach($markets as $market){
    //         $sql = "SELECT SUM(points) FROM betting_history WHERE betting_history.market_id = :market_id";
    //         $query = $con -> prepare($sql);
    //         $query->bindparam(':market_id', $market->market_id, PDO::PARAM_STR);
    //         if($query->execute()){
    //             $total_bid_placed = $query->fetchAll(PDO::FETCH_OBJ);
    //         }
    //         if(date($market->market_closetime) > date("H-i-s")){
    //             $status = "open";
    //         }else{
    //             $status = "close";
    //         }
    //         $market_details = [
    //             "market_fullname" => $market->market_fullname,
    //             "market_opentime" => $market->market_opentime,
    //             "market_closetime" => $market->market_closetime,
    //             "market_status" => $status,
    //             "market_bids" => $total_bid_placed
    //         ];
    //         array_push($market_data, $market_details);
    //     }
    //     $status = 200;
    //     $response = [
    //         "msg" => $market_data
    //     ];
    // }else{
    //     $status = 203;
    //     $response = [
    //         "msg" => "data can't be fetched"
    //     ];
    // }





    // //total user
    // $sql = "SELECT COUNT(user_id) FROM user_table";
    // $query = $con -> prepare($sql);
    // if($query->execute()){
    //     $total_user = $query->fetchAll(PDO::FETCH_ASSOC)[0];
    //     $status = 200;
    //     $response = [
    //         "msg" => $total_user
    //     ];
    // }else{
    //     $status = 203;
    //     $response = [
    //         "msg" => "Can't fetch users."
    //     ];
    // }



    // //Total deposit on a particular day
    // $transaction_type = "deposit";
    // $sql = "SELECT SUM(transaction_amount) FROM transaction_details 
    // WHERE DATE(created_at) = CURDATE() AND transaction_type =:transaction_type";
    // $query = $con -> prepare($sql);
    // $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    // if($query->execute()){
    //     $total_deposits = $query->fetchAll(PDO::FETCH_ASSOC)[0];
    //     $status = 200;
    //     $response = [
    //         "msg" => $total_deposits
    //     ];
    // }else{
    //     $status = 203;
    //     $response = [
    //         "msg" => "Can't fetch users."
    //     ];
    // }

        // //total_bid_amount
    // $sql = "SELECT SUM(points) FROM betting_history 
    //  WHERE DATE(created_at) = CURDATE()";
    // $query = $con -> prepare($sql);
    // if($query->execute()){
    //     $total_bid_amount = $query->fetchAll(PDO::FETCH_ASSOC)[0];
    //     $status = 200;
    //     $response = [
    //         "msg" => $total_bid_amount
    //     ];
    // }else{
    //     $status = 203;
    //     $response = [
    //         "msg" => "Can't fetch users."
    //     ];
    // }



        // //todays_biddings
    // $sql = "SELECT COUNT(bet_id) FROM betting_history 
    //  WHERE DATE(created_at) = CURDATE()";
    // $query = $con -> prepare($sql);
    // if($query->execute()){
    //     $todays_biddings = $query->fetchAll(PDO::FETCH_ASSOC)[0];
    //     $status = 200;
    //     $response = [
    //         "msg" => $todays_biddings
    //     ];
    // }else{
    //     $status = 203;
    //     $response = [
    //         "msg" => "Can't fetch users."
    //     ];
    // }