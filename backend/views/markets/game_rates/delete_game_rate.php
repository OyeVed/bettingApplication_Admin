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
    //find the market to delete
    $rate_id = $_POST['rate_id'];
    //fetch details from market
    $sql = "DELETE FROM game_rate_table
    WHERE rate_id = :rate_id";
    $query = $con -> prepare($sql);
    $query->bindparam("rate_id", $rate_id, PDO::PARAM_STR);

    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Game got deleted."
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Game can't be deleted now."
        ];
    }
    

}