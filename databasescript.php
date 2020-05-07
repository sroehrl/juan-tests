<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class objDatabaseConnection {

    private $connection;
    private $username="";
    private $password="";
    private $database="";

    function __construct() {
        $credentialFile = file_get_contents(__DIR__.'/.env');
        $rows = explode("\n",$credentialFile);
        foreach ($rows as $row){
            $parts = explode('=', $row);
            switch(trim($parts[0])){
                case 'DATABASE_USER':
                    $this->username = trim($parts[1]);
                    break;
                case 'DATABASE_PASSWORD':
                    $this->password = trim($parts[1]);
                    break;
                case 'DATABASE_NAME':
                    $this->database = trim($parts[1]);
            }
        }
    }


    function openConnection()
    {
        $this->connection = new mysqli('localhost',$this->username,$this->password,$this->database);
        $this->connection->set_charset("utf8");


        if ($this->connection -> connect_errno) {
            //echo "Failed to connect to MySQL: " . $this->$connection -> connect_error;
            $error=$this->connection -> connect_error;
            $pos = strpos($error, 'Unknown database');
            if ($pos !== false) {
                $this->createDB();
                echo "creating database";
            }
            exit();
        } else {
            //echo "connection successful";
        }
        return $this->connection;
    }

    function readData($sql){
        $data = [];

        $result = $this->connection->query($sql);
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }


}

