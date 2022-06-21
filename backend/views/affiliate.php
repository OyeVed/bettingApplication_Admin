<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// getting token from cookie
$token = $_COOKIE["admin_jwt"];

// checking is the user authorized 
if(auth($token)){

    $sql = "SELECT user_fullname, referral_code, referred_by FROM user_table ";
    $query = $con -> prepare($sql);
    $query->execute();

    if($query->rowCount() === 0){

        $status = 203;
        $response = [
            "msg" => "Bad Request - User does not exist"
        ];

    }else{

        $affiliate_list = $query->fetchAll(PDO::FETCH_OBJ);

        if($affiliate_list){
            $status = 200;
            $response = [
                "msg" => "List fetched successfully",
                "affiliate_list" => $affiliate_list
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Affiliate list can't be fetched "
            ];
        }

    }
}