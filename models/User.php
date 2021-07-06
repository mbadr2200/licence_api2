<?php 

    Class User {

        // database connection 
        private $conn;
        private $table = "users";

        // user proprties 
        public $username ;
        public $password ;

        public function __construct ($db)
        {
            $this->conn = $db;
        }

        public function createUser(){



            $query = "INSERT INTO `users` (`username`, `password`) VALUES ('$this->username', '$this->password')";

            $result = $this->conn->query($query);
            $affected_rows = $this->conn->affected_rows;

            if($affected_rows > 0)
            {
                return array(
                    "message" => "user added ."
                ); 
            }else
            {
                return array(
                    "message" => "cannot add user , error : " . $this->conn->error
                ); 
            }
            




        }

        public function getAllUsers()
        {
            $users = [] ;

            $query = "SELECT * FROM users ";


            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {
                // find users 
                while($user = $result->fetch_assoc())
                {
                    // make user array
                    array_push($users , $user);
                }
                return array(
                    "message" => "Found {$result->num_rows} user ",
                    "data" =>$users
                    
                );
            }
            else
            {
                // no user exists
                header('X-PHP-Response-Code: 400', true, 400);
                return array(
                    "message" => "No users found . "
                );
            }
        }

        public function getUserById($id)
        {
            $query = "SELECT * FROM users WHERE users.id = $id";


            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {

                // found the user 
                $user = $result->fetch_assoc();
                return  array(
                    "message" => "user found  ",
                    $user
                );

            }
            else
            {
                // user dosent exists
                header('X-PHP-Response-Code: 400', true, 400);
                return array(
                    "message" => "No user found . "
                );
            }

        }

        public function getUserByUsername($username)
        {
            $query = "SELECT * FROM users WHERE users.username = '$username'";


            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {

                // found the user 
                $user = $result->fetch_assoc();
                return  array(
                    "message" => "user found  ",
                    $user
                );

            }
            else
            {
                // user dosent exists
                header('X-PHP-Response-Code: 400', true, 400);
                return array(
                    "message" => "No user found . "
                );
            }

        }

    }
