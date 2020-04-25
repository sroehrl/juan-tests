<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class objDatabaseConnection {

    private $connection;
    private $username="";
    private $password="";

    function __construct() {

        $this->username="root";
        $this->password="";
    }


    function openConnection()
    {
        $this->connection = new mysqli('localhost',$this->username,$this->password,'readcomptest');
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

    function CreateDB(){

        //drop DATABASE esl_parents;;

        $this->connection = new mysqli('localhost',$this->username,$this->password);
        //only connection to mysql and don't set a database
        $this->connection->set_charset("utf8");

        $sqlstatement="Create database esl_parents;";

        $this->connection->query($sqlstatement);

        $sqlstatementUse="Use esl_parents;";
        $this->connection->query($sqlstatementUse);

        $sqlstatement2 = "CREATE TABLE `esl_parents` (
          `id` int(11) NOT NULL,
          `family` varchar(60) DEFAULT NULL,
          `chromebook` decimal(9,0) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->connection->query($sqlstatement2);

        $sqlstatement3 = "INSERT INTO `esl_parents` (`id`, `family`, `chromebook`) VALUES
        (1, 'Martinez', '4'),
        (2, 'Hernendez', '3'),
        (3, 'Gonzalez', '19')";
        $this->connection->query($sqlstatement3);

        $sqlstatement4 = "ALTER TABLE `esl_parents` ADD PRIMARY KEY (`id`)";
        $this->connection->query($sqlstatement4);

        $sqlstatement5 = "ALTER TABLE `esl_parents`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6";
        $this->connection->query($sqlstatement5);

        $this->connection->close();

        //$this->openConnection();

        header("Location: test.php");
        exit();


    }



    function insert($family, $chromebook)
    {



        $f = $this->connection->real_escape_string($family);
        $c = $this->connection->real_escape_string($chromebook);

        $this->connection->query("INSERT INTO esl_parents SET family = '".$f."', chromebook='".$c."'");
        $auto_id = mysqli_insert_id($this->connection);

        return $auto_id;

    }

    function update($id,$family,$chromebook){

        ///$this->openConnection();
        $this->connection->set_charset("utf8");

        $f = $this->connection->real_escape_string($family);
        $c = $this->connection->real_escape_string($chromebook);

        $this->connection->query("Update esl_parents SET family = '".$f."', chromebook=".$c." where id=".$id);

        return $id;
    }

    function delete($id){
        // $this->openConnection();
        $this->connection->query("delete from esl_parents where id=".$id);


    }

    function read($sql)
    {
        // $this->openConnection();
        $rows = [];
        $results =  $this->connection->query($sql);
        while ($row = $results->fetch_assoc()){
            $rows[] = $row;
        }
        return $rows;
    }

}

