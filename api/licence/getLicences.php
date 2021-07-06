<?php
session_start();
require_once("../../config/functions.php");
//isLogged();

// headers 
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header("Access-Control-Allow-Method: POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,,Authorization, X-Requested-With");

// includes 
include_once "../../config/Database.php";
include_once "../../models/Licence.php";

// database 
$database = new Database();
$db = $database->connect();

$Licence = new Licence($db);

if(isset($_GET["id"]))
{
    // if there is user id param in the link 

    if(strlen($_GET["id"]) > 0)
    {
        // if valid licence id 
        // get licence with the id 
        $Licence->id = $_GET["id"];
        $licence = $Licence->getLicenceById();
        print_r(json_encode($licence));
        
        
    }
    else
    {
        // if ther user id param in the link is empty string return all licences 
        $licences = $Licence->getAllLicence();
        print_r(json_encode($licences));
    }
    
}else
{
     // if there is no user id param in the link return all licences 
     $licences = $Licence->getAllLicence();
     echo json_encode($licences);
}
