<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){

    $phone_number = $_POST['phone_number'];
    $old_password = md5($_POST['old_password']);
    $new_password = $_POST['new_password'];
    
    $sql = "SELECT * FROM admin_user_table WHERE admin_user_table.admin_phonenumber=:phone_number";
    $query = $con -> prepare($sql);
    $query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $query->execute();
    
    if($query->rowCount() === 0){
    
        $status = 203;
        $response = [
            "msg" => "Bad Request - User does not exist"
        ];
    
    }else{
    
        $admin = $query->fetchAll(PDO::FETCH_OBJ)[0];
    
        if($admin->admin_password === $old_password ){
            $new_password = md5($new_password);
            $sql = "UPDATE admin_user_table SET admin_password=:new_password WHERE admin_user_id=4";
            $query = $con -> prepare($sql);
            $query->bindParam(':new_password', $new_password, PDO::PARAM_STR);
            $status = 200;
            $response = [
                "msg" => "Password updated successfully",
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Old password does not match"
            ];
        }
    
    }
}

