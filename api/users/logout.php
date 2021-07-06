<?php

session_start();
require_once("../../config/functions.php");
isLogged();

// headers 
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization , X-Requested-With");


$_SESSION["user_id"] =  null;
$_SESSION["user_name"] =  null;
print_r(json_encode(array("message" => "User logged out .")));


?>