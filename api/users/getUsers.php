<?php
session_start();
require_once("../../config/functions.php");
isLogged();

// headers 
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header("Access-Control-Allow-Method: GET");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization , X-Requested-With");

// includes 
include_once "../../config/Database.php";
include_once "../../models/User.php";

// database 
$database = new Database();
$db = $database->connect();

$User = new User($db);

if(isset($_GET["id"]))
{
    // if there is user id param in the link 

    if(strlen($_GET["id"]) > 0)
    {
        // if valid user id 
        
        $user = $User->getUserById($_GET["id"]);
        print_r(json_encode($user));
    }
    else
    {
        // if ther user id param in the link is empty string return all users 
        $users = $User->getAllUsers();
        print_r(json_encode($users));
    }
    
}else
{
     // if there is no user id param in the link return all users 
    $users = $User->getAllUsers();
    print_r(json_encode($users));
}
