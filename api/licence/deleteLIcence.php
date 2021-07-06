<?php

session_start();
require_once("../../config/functions.php");
//isLogged();

// headers 
//  header("Access-Control-Allow-Origin:http://localhost:8080");

//header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Content-Type:application/json");
//header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization , X-Requested-With,Origin");

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

if (!isset($data->id) || !strlen($data->id) > 0) {
    array_push($validation_errors, array("message" => "please provide licence id "));
}

if (!empty($validation_errors)) {
    // if any errors 
    header('X-PHP-Response-Code: 400', true, 400);
    print_r(json_encode($validation_errors));
    die();

}else{
    
    // the licence field all exists
    $licence->id = htmlspecialchars(strip_tags($data->id));
    print_r(json_encode($licence->deleteLicence()));
}

?>