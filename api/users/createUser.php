<?php

// headers 
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization , X-Requested-With");

// includes 
include_once "../../config/Database.php";
include_once "../../models/User.php";

// database 
$database = new Database();
$db = $database->connect();

// get user modal
$User = new User($db);

// get the username & password
$data = json_decode(file_get_contents("php://input"));

$validation_errors = [];
if (!isset($data->username) || strlen($data->username) < 3) {
    array_push($validation_errors, array("message" => "please provide a username with at least 3 character "));
}
if (!isset($data->password) || strlen($data->password) < 8) {
    array_push($validation_errors, array("message" => "please provide a password with at least 8 character "));
}


if (!empty($validation_errors)) {
    // if any errors 
    header('X-PHP-Response-Code: 400', true, 400);
    print_r(json_encode($validation_errors));
    die();

} else {
    // the username and password is valid

    $User->username =  htmlspecialchars(strip_tags($data->username));
    $password = htmlspecialchars(strip_tags($data->password));
    $hased_password = password_hash($password,PASSWORD_BCRYPT , ["cost" => 10]);
    $User->password =  $hased_password;



    $result = $User->createUser();
    print_r(json_encode($result));
}
