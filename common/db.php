<?php
class db{
    private $pdo;
    private $host;
    private $db;
    private $dbuser;
    private $db_pw;
    function connect_db(){
        $this -> host = $_SERVER["HTTP_HOST"];
        $this -> db = "smartstockbook";
        $this -> dbuser = "root";
        $this -> db_pw = "";
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try{
            $this -> pdo = new PDO("mysql:host=$this->host;dbname=$this->db", $this -> dbuser, $this->db_pw);
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
        return $create -> execute() ? true : $stmt;
    }


    function insert_data($tbl_name, $data){
        if(is_array($data)){
            $columns = array_keys($data);
            $values = array_values($data);
            $length = count($data);
            $stmt = "INSERT INTO $tbl_name(";
                //insert into table_name(                printed
            $end_column = end($columns);
            $end_data = end($values);
            foreach($columns as $col){
                $stmt .=  (($col != $end_column) ? $col.",  "    :    $col." ) ");
            }
            //insert into table_name(col1, col2, col3)                  printed
            $stmt.= " VALUES( ";
            foreach($columns as $col){
                $stmt .=  (($col != $end_column)    ?   " ?,"   :   " ?)"  );
            }
            //statement printed
            $insert = $this -> pdo -> prepare($stmt);
            return ($insert -> execute($values)) ? true : false;
        }
    }

    function update($table, $id, $data){
        $keys = array_keys($data);
        $values = array_values($data);
        $length = count($keys);

        $sql = "UPDATE $table SET ";
        $i = 1;
        foreach ($keys as $k) {
            $sql = $sql . " $k = ? ";
            if ($i != $length) {
                $sql = $sql . ", ";
            }
            $i++;
        }

        $sql = $sql . " WHERE id = ?";

        $values[] = $id;
        $stmt = $this -> pdo -> prepare($sql);
        if($stmt->execute($values)){
            return true;
        }
        else{
            return false;
        }
    }


    function delete($table, $id){
        $stmt = $this -> pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    }


    function select_all($table){
        $stmt = $this -> pdo->prepare("SELECT * FROM $table");
        if(!$stmt->execute())
            return "table not found";
        return $stmt->fetchAll();
    }

    function where($table, $col, $opr, $val, $all = true){
        $stmt = $this -> pdo->prepare("SELECT * FROM $table WHERE $col $opr ?");
        $stmt->execute([$val]);

        if ($all) {
            return $stmt->fetchAll();
        }

        return $stmt->fetch();
    }

    function table_exists($table){
        $stmt = $this -> pdo -> prepare("SHOW TABLES LIKE ?");
        $stmt -> execute([$table]);
        return ($stmt -> rowCount() > 0) ? true : false;
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
$stmt = $pdo -> prepare("INSERT INTO usars(name, email, phone, password, address) VALUES(?, ?, ?,?)");
if(!$stmt->execute($data)){
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


/*
$name = 'Kri"s"hn"a';
$email = "kisnatwari@gmail.com";
$phone = "9816439892";
$password = "thisispassword";
$role = "admin";
$address = "Kawasoti - 5, Nawalpur";
if($db -> insert_data("users",compact("address", "name", "email", "password", "phone")))
    echo "success";
else
    echo "failed";
*/

//    $db -> delete("users", "1");
?>