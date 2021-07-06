<?php

// headers 
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization , X-Requested-With");

// includes 
include_once "../../config/Database.php";
include_once "../../models/Licence.php";

// database 
$database = new Database();
$db = $database->connect();

// licence model
$licence = new Licence($db);

// get the licence key & ip
$data = json_decode(file_get_contents("php://input"));


// check for the key is present 
$validation_errors = [];

if (!isset($data->key) || !strlen($data->key) > 0) {
    array_push($validation_errors, array("message" => "please provide a licence "));
}

if (!isset($data->ip_address) || !strlen($data->ip_address) > 0) {
    array_push($validation_errors, array("message" => "please provide an ip adress "));
}

if (!empty($validation_errors)) {
    // if any errors 
    header('X-PHP-Response-Code: 400', true, 400);
    print_r(json_encode($validation_errors));
    die();
}else{
    // the licence and the ip adress is exists 

    $licence->key = htmlspecialchars(strip_tags($data->key));
    $licence->ip_address = htmlspecialchars(strip_tags($data->ip_address));

    print_r(json_encode($licence->validate_licence()));
}

