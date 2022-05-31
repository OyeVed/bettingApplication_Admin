<?php

// import db connection
require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$phone_number = $_POST['phone_number'];
$password = md5($_POST['password']);
$email_id = $_POST['email'];
$full_name = $_POST['full_name'];

$sql = "SELECT * FROM admin_user_table WHERE admin_user_table.admin_email_id=:email_id OR admin_user_table.admin_phonenumber=:phone_number";
$query = $con -> prepare($sql);
$query->bindParam(':email_id', $email_id, PDO::PARAM_STR);
$query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    $sql = "INSERT INTO 
    admin_user_table (admin_phonenumber, admin_password, admin_email_id, admin_fullname) VALUES 
    ( :admin_phonenumber, :admin_password, :admin_email_id, :admin_fullname)";
    $query = $con -> prepare($sql);
    $query->bindParam(':admin_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':admin_password', $password, PDO::PARAM_STR);
    $query->bindParam(':admin_email_id', $email_id, PDO::PARAM_STR);
    $query->bindParam(':admin_fullname', $full_name, PDO::PARAM_STR);

    if($query->execute()){
        $user = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "User created successfully",
            "user" => [
                "admin_phone_number" => $phone_number,
                "admin_fullname" => $full_name,
                "email_id" => $email_id
            ]
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal Server Error - User creation failed"
        ];
    }

}else{

    $status = 203;
    $response = [
        "msg" => "Bad Request - User already exists"
    ];

}