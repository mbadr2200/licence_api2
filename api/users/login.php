<?php
session_start();
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
    // CHECK FOR USER IN DATABASE 

    $username =  htmlspecialchars(strip_tags($data->username));
    $password = htmlspecialchars(strip_tags($data->password));

    $user = $User->getUserByUsername($username);

    if(isset($user[0]))
    {

        $hased_password = $user[0]["password"];

        if(password_verify($password,$hased_password))
        {
            $_SESSION["user_id"] =  $user[0]["id"];
            $_SESSION["user_name"] =  $user[0]["username"];
            print_r(json_encode(array("message" => "User logged in .")));

        }else
        {
            header('X-PHP-Response-Code: 400', true, 400);
            $result = array("message" => "User/password invalid ");
            print_r(json_encode($result));
        }

        
    }
    else
    {
        header('X-PHP-Response-Code: 400', true, 400);
        $result = array("message" => "User/password invalid ");
        print_r(json_encode($result));
    }
    
}


?>