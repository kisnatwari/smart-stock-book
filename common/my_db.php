<?php
class db{
    private $pdo;
    function connect_db(){
        $host = $_SERVER["HTTP_HOST"];
        $db = "smartstockbook";
        $dbuser = "root";
        $db_pw = "";
        $root = "http://localhost/ims/";
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try{
            $this -> pdo = new PDO("mysql:host=$host;dbname=$db", $dbuser, $db_pw);
            return $this -> pdo;
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }



        
    function create_table($tbl_name, $column_details){
        $end_column = end($column_details);
        $stmt = "create table $tbl_name(";
        foreach($column_details as $col){
            $stmt .= (($col != $end_column) ? $col.",  " : $col." )");
        }
        $create = $this -> pdo -> prepare($stmt);
        return $create -> execute() ? true : false;
    }


    function insert_data($tbl_name, $columns, $data){
        if(is_array($columns) && is_array($data)){
            if(count($columns) == count($data)){
                $length = count($columns);
                $stmt = "INSERT INTO $tbl_name(";
                    //insert into table_name(                printed
                $end_column = end($columns);
                $end_data = end($data);
                foreach($columns as $col){
                    $stmt .=  (($col != $end_column) ? $col.",  "    :    $col." ) ");
                }
                //insert into table_name(col1, col2, col3)                  printed
                $stmt.= " VALUES( ";
                foreach($columns as $col){
                    $stmt .=  (($col != $end_column)    ?   " :$col, "   :   " :$col )"  );
                }
                //statement printed
                $insert = $this -> pdo -> prepare($stmt);
                for($i = 0; $i<$length; $i++){
                    $insert -> bindParam(":".$columns[$i], $data[$i]);
                }
                return ($insert -> execute()) ? true : false;
            }
        }
    }
}

    $db = new db();
    $pdo = $db -> connect_db();
    
/*
$name = "Krishna";
$email = "kisnatwari@gmail.com";
$phone = 'asdfadsf';
$password = "fdsa";
$address = "Kawasoti";
$stmt = $pdo -> prepare("INSERT INTO usars(name, email, phone, password, address) VALUES(:name, :email, :phone, :password, :address)");
$stmt -> bindParam(":name", $name);
$stmt -> bindParam(":email", $email);
$stmt -> bindParam(":phone", $phone);
$stmt -> bindParam(":password", $password);
$stmt -> bindParam(":address", $address);
if(!$stmt->execute()){
    $create = $pdo -> prepare("create table usars(
        id int primary key AUTO_INCREMENT,
        name varchar(100),
        email varchar(100),
        phone varchar(17),
        password varchar(100),
        address varchar(200)
)");
    $create -> execute();
    $stmt -> execute();
}
print_r( $stmt);
*/

//$db -> create_table("users", [" id int primary key AUTO_INCREMENT", "name varchar(100)", "email varchar(100)", "phone varchar(17)", "password varchar(100)", "address varchar(200)"]);



?>