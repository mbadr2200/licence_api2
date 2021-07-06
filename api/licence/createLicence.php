<?php

// headers 
header("Access-Control-Allow-Origin:http://localhost:8080/");
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
    array_push($validation_errors, array("message" => "key is missing  "));
}

if (!isset($data->expiry_date) || !strlen($data->expiry_date) > 0) {
    array_push($validation_errors, array("message" => "expiry date is missing "));
}


if (!isset($data->status) || !strlen($data->status) > 0) {
    array_push($validation_errors, array("message" => "status is missing "));
}
if (!isset($data->ip_address) ) {

    $data->ip_address = null;

}

if (!empty($validation_errors)) {
    // if any errors 
    header('X-PHP-Response-Code: 400', true, 400);
    print_r(json_encode($validation_errors));
    die();
}else{
    // the licence field all exists

    $licence->key = htmlspecialchars(strip_tags($data->key));
    $licence->ip_address = htmlspecialchars(strip_tags($data->ip_address));
    $licence->status = htmlspecialchars(strip_tags($data->status));
    $licence->expiry_date = htmlspecialchars(strip_tags($data->expiry_date));

    print_r(json_encode($licence->createLicence()));
}

?>