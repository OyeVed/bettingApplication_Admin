<?php


$database_tables = array(
    "
    CREATE TABLE IF NOT EXISTS `admin_user_table` (
        `admin_user_id` INT(11) PRIMARY KEY AUTO_INCREMENT,
        `admin_password` VARCHAR(255) NOT NULL,
        `admin_email_id` varchar(255) NOT NULL,
        `admin_phonenumber` VARCHAR(255) NOT NULL,
        `admin_fullname` VARCHAR(255) NOT NULL,
        `profile_image` BLOB,
        `otp` INTEGER(10),
        `otp_created_at` VARCHAR(255),
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL
    )",
    "
    CREATE TABLE IF NOT EXISTS `win_history` (
        `win_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `market_id` INT(255),
        `user_id` INT(255),
        `game_name` VARCHAR(255) NOT NULL,
        `win_amount` INT(255) NOT NULL,
        `game_total_collection` INT(255) NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        FOREIGN KEY (market_id) 
            REFERENCES market_table(market_id)
            ON DELETE CASCADE 
            ON UPDATE CASCADE, 
        FOREIGN KEY (user_id) 
            REFERENCES user_table(user_id)
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    )",
    "
    CREATE TABLE IF NOT EXISTS `market_results` (
        `result_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `market_id` INT(255),
        `results` VARCHAR(255) NOT NULL,
        `result_date` VARCHAR(10) ,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        FOREIGN KEY (market_id) 
            REFERENCES market_table(market_id)
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    )",
    "
    CREATE TABLE IF NOT EXISTS `pending_request` (
        `request_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `user_id` INT(255),
        `upi_id` VARCHAR(255) NOT NULL,
        `withdrawal_amount` VARCHAR(255) NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        FOREIGN KEY (user_id) 
            REFERENCES user_table(user_id)
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    )"
 
);

require_once("dbcon.php");

foreach ($database_tables as $database_table) {
    try{

        $con->exec($database_table);
        
    } catch(PDOException $e){

        echo "Error creating table: " . $database_table . "<br>" . $e->error;
        // print_r($e);

    }
}