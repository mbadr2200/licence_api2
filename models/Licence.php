<?php

class Licence
{
    // database connection 
    private $conn;
    private $table = "licence";

    // licence proprties 


    public $expiry_date;
    public $ip_address;
    public $status;
    public $key;
    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // authinticate the licence

    public function validate_licence()
    {

        $query = "SELECT * FROM licence where licence.key ='$this->key'";


        $result = $this->conn->query($query);


        if ($result->num_rows > 0) {

            $result = $result->fetch_assoc();

            // if licence exists 
            //check if the status of licence is valid 

            if ($result["status"] == "valid") {

                $current_date = time();
                $expiry_date = strtotime($result["expiry_date"]);


                if ($expiry_date >= $current_date) {
                    // the licence is not expired 
                    //check if the licence has ip address 
                    if ($result["ip"]) {
                        // the licence has registred ip in database

                        if (!isset($this->ip_address)) {
                            header('X-PHP-Response-Code: 400', true, 400);
                            $this->status = "invalid";
                            return array("message" => "ip address is required");
                        }

                        if ($this->ip_address == $result["ip"]) {
                            // the ip is identical to the ip in the database
                            $this->status = "valid";
                            return array(
                                "message" => "the licence is valid and with the same ip address ",
                                "status" => "valid"
                            );
                        } else {
                            // the ip is not the same as the database
                            header('X-PHP-Response-Code: 401', true, 401);
                            $this->status = "invalid";
                            return array(
                                "message" => "the licence is not working in this ip address ",
                                "status" => "invalid"
                            );
                        }
                    } else {
                        // the licence ip address is not set yet    
                        $update_query = "UPDATE licence SET licence.ip = '" . $this->ip_address .  "' WHERE licence.id = " . $result['id'];

                        $update_result = $this->conn->query($update_query);
                        $affected_rows = $this->conn->affected_rows;
                        if ($affected_rows > 0) {
                            // the ip address updated 
                            $this->status = "valid";
                            return array(
                                "message" => "the ip address has been set to {$this->ip_address}",
                                "status" => "valid"
                            );
                        } else {
                            header('X-PHP-Response-Code: 500', true, 500);
                            return array("message" => "failed to update the ip address , error : {$this->conn->error} ");
                        }
                    }
                } else {
                    header('X-PHP-Response-Code: 401', true, 401);
                    return array(
                        "message" => "the licence is expired",
                        "status" => "invalid"
                    );
                }
            } else {
                $this->status = "invalid";
                header('X-PHP-Response-Code: 401', true, 401);
                return array(
                    "message" => "the licence is invalid",
                    "status" => "invalid"
                );
            }
        } else {

            // if licence not found 
            header('X-PHP-Response-Code: 404', true, 404);
            return array("message" => "the licence key you provide is not exists ");
        }
    }

    public function getAllLicence()
    {
        $licences = [];

        $query = "SELECT * FROM `licence` ";


        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            // find users 
            while ($licence = $result->fetch_assoc()) {
                // make user array
                array_push($licences, $licence);
            }

            return array(
                "message" => "Found {$result->num_rows} Licence ",
                "data" => $licences
            );
        } else {
            // no user exists
            header('X-PHP-Response-Code: 400', true, 400);
            return array(
                "message" => "No licences found . "
            );
        }
    }

    public function getLicenceById()
    {
        

        $query = "SELECT * FROM `licence` WHERE licence.id = $this->id";


        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            // find users 
            while ($licence = $result->fetch_assoc()) {
                // make user array
                return array(
                    "message" => "Found {$result->num_rows} Licence ",
                    "data" => $licence
                );
            }
        } else {
            // no user exists
            header('X-PHP-Response-Code: 400', true, 400);
            return array(
                "message" => "No licences found . "
            );
        }
    }

    public function editLicence()
    {

        $query = "SELECT * FROM licence where licence.id ='$this->id'";


        $result = $this->conn->query($query);


        if ($result->num_rows > 0) {
            $update_query = "UPDATE `licence` SET `key` = '$this->key', `expiry_date` = '$this->expiry_date', `ip` = '$this->ip_address', `status` = '$this->status' WHERE `licence`.`id` = $this->id";
            $update_result = $this->conn->query($update_query);
            $affected_rows = $this->conn->affected_rows;

            if ($affected_rows >= 0 && !$this->conn->error) {
                // the licence deleted successfuly 

                return array(
                    "message" => "Licence with the id $this->id Updated ."
                );
            } else {
                header('X-PHP-Response-Code: 500', true, 500);
                return array(
                    "message" => "failed to Update the licence",
                    "error" => $this->conn->error
                );
            }
        } else {
            header('X-PHP-Response-Code: 400', true, 400);
            return array(
                "message" => "There is no licence with this id",
                "error" => $this->conn->error
            );
        }
    }

    public function createLicence()
    {

        $create_query = "INSERT INTO licence (`key`, `expiry_date`, `ip`, `status`) VALUES ('$this->key', '$this->expiry_date', '$this->ip_address', '$this->status');";
        $createResult = $this->conn->query($create_query);
        $affected_rows = $this->conn->affected_rows;

        print_r($createResult);

        if ($affected_rows > 0) {
            // the licence created successfulyy 

            return array(
                "message" => "Licence Created ."
            );
        } else {
            header('X-PHP-Response-Code: 500', true, 500);
            return array(
                "message" => "failed to create the licence",
                "error" => $this->conn->error
            );
        }
    }

    public function deleteLicence()
    {
        $delete_query = "DELETE FROM licence WHERE licence.id = $this->id";

        $delete_result = $this->conn->query($delete_query);
        $affected_rows = $this->conn->affected_rows;

        if ($affected_rows > 0) {
            // the licence deleted successfulyy 

            return array(
                "message" => "Licence with the id $this->id deleted ."
            );
        } else {
            header('X-PHP-Response-Code: 500', true, 500);
            return array(
                "message" => "failed to delete the licence",
                "error" => $this->conn->error
            );
        }
    }
}
