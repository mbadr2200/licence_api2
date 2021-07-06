<?php
function isLogged()
{
    
    if (!isset($_SESSION["user_id"])) {
        header('X-PHP-Response-Code: 401', true, 401);
        print_r(json_encode(array("message" => "Please login first .")));
        die();
    }
}

function redirect($url)
{
    header("Location:{$url}");
}
