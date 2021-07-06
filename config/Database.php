<?php

class Database
{

    private $db_host = "181.215.242.81:35840";
    private $db_user = "admin";
    private $db_password = "1wPw4Ami";
    private $db_name = "licence_api";
    private $conn;

    public function connect()
    {
        $this->conn = null;


        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name) or die("Connect failed: %s\n" . $this->conn->error);

        // check if connection failed 

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
