<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){
    $market_id = $_POST['market_id'];
    $result = $_POST['result'];

    $open_single_digit = $result[4];
    $close_single_digit = $result[5];
    $jodi_digit = substr($result,4,2);
    $open_pana = substr($result,0,3);
    $close_pana = substr($result,7,3);
    $half_sangam_a = substr($result,0,3)."-".$result[5];
    $half_sangam_b = $result[4]."-".substr($result,7,3);
    $full_sangam = substr($result,0,3)."-".substr($result,7,3);

    $sql = "SELECT bh.user_id, bh.market_id, bh.points, bh.game_name, grt.reward_factor FROM betting_history as bh
    LEFT JOIN game_rate_table as grt
    ON bh.game_name = grt.game_name
    WHERE bh.game_name = 'Single Digit' AND bh.game_type = 'open' AND bh.number = :open_single_digit OR
    bh.game_name = 'Single Digit' AND bh.game_type = 'close' AND bh.number = :close_single_digit OR
    bh.game_name = 'Jodi Digit' AND bh.game_type = '' AND bh.number = :jodi_digit OR
    bh.game_name = 'Single Pana' OR bh.game_name = 'Double Pana' OR bh.game_name = 'Triple Pana'  AND bh.game_type = 'open' AND bh.number = :open_pana OR
    bh.game_name = 'Single Pana' OR bh.game_name = 'Double Pana' OR bh.game_name = 'Triple Pana'  AND bh.game_type = 'close' AND bh.number = :close_pana OR
    bh.game_name = 'Half Sangam' AND bh.game_type = '' AND bh.number = :half_sangam_a OR bh.number = :half_sangam_b OR
    bh.game_name = 'Full Sangam' AND bh.game_type = '' AND bh.number = :full_sangam";
    $query = $con -> prepare($sql);
    $query->bindParam(':open_single_digit', $open_single_digit, PDO::PARAM_STR);
    $query->bindParam(':close_single_digit', $close_single_digit, PDO::PARAM_STR);
    $query->bindParam(':jodi_digit', $jodi_digit, PDO::PARAM_STR);
    $query->bindParam(':open_pana', $open_pana, PDO::PARAM_STR);
    $query->bindParam(':close_pana', $close_pana, PDO::PARAM_STR);
    $query->bindParam(':half_sangam_a', $half_sangam_a, PDO::PARAM_STR);
    $query->bindParam(':half_sangam_b', $half_sangam_b, PDO::PARAM_STR);
    $query->bindParam(':full_sangam', $full_sangam, PDO::PARAM_STR);
    $query->execute();

    $users_who_won = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach($users_who_won as $user){
        $winning_amount = $user['reward_factor'] * $user['points'];
        // print_r($user);
        // print_r($winning_amount);
        // echo "\n";

        $transaction_type = "won";
        $transaction_amount = $winning_amount;
        $available_amount = 0;
        $transaction_name = "You won ".$winning_amount."rs in ".$user['game_name'];
         //calculating funds available
        $query = $con->prepare(" SELECT amount_in_wallet FROM transaction_details WHERE user_id=:user_id
        ORDER BY transaction_id DESC LIMIT 1 ");
        $query->bindParam(':user_id', $user['user_id'], PDO::PARAM_STR);
        if($query->execute()){
            $query->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $query->fetch()) {
                $available_amount = $row['amount_in_wallet'];
            }
            $new_wallet_balance = $available_amount + $winning_amount;
        }
        else{
            $status = 203;
            $response = [
                "msg" => "Can't fetch user's wallet balance."
            ];
        }
        $sql = "INSERT INTO transaction_details (user_id, transaction_type, transaction_name, transaction_amount, amount_in_wallet)
        VALUES (:user_id, :transaction_type, :transaction_name, :transaction_amount, :amount_in_wallet)";
        /* WHERE mr.created_at = :curr_date > */
        $query = $con -> prepare($sql);
        $query->bindParam(':user_id', $user['user_id'], PDO::PARAM_STR);
        $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
        $query->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
        $query->bindParam(':transaction_amount', $transaction_amount, PDO::PARAM_STR);
        $query->bindParam(':amount_in_wallet', $new_wallet_balance, PDO::PARAM_STR);
        if($query->execute()){
            $status = 200;
            $response = [
                "msg" => "Winning amount added to the wallet"
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Transaction failed for user_id ".$user['user_id']
            ];
        }

        $sql = "INSERT INTO win_history (market_id, user_id, game_name, win_amount)
        VALUES (:market_id, :user_id, :game_name, :win_amount)";
        /* WHERE mr.created_at = :curr_date > */
        $query = $con -> prepare($sql);
        $query->bindParam(':user_id', $user['user_id'], PDO::PARAM_STR);
        $query->bindParam(':market_id', $user['market_id'], PDO::PARAM_STR);
        $query->bindParam(':game_name', $user['game_name'], PDO::PARAM_STR);
        $query->bindParam(':win_amount', $winning_amount, PDO::PARAM_STR);
        if($query->execute()){
            $status = 200;
            $response = [
                "msg" => "Winning data stored in win history"
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Winning data can't be stored for user_id ".$user['user_id']
            ];
        }

        

    }
    
}


















// $curr_date = date("Y-m-d");
// $sql = "SELECT created_at FROM betting_history as a WHERE user_id=:user_id AND CAST(created_at AS DATE) = :curr_date";
// $query = $con -> prepare($sql);
// $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
// $query->bindParam(':curr_date', $curr_date, PDO::PARAM_STR);
// $query->execute();
// $created_at = $query->fetchAll(PDO::FETCH_ASSOC);