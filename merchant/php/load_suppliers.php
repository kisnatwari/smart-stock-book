<?php 
require_once "../../common/db.php";
require_once "../../common/imp_functions.php";
header("Content-type: application/json");
$merchant_id = $_SESSION["logged_in_merchant"];
$stmt = $pdo -> prepare("SELECT id, name, email, contact, address FROM suppliers WHERE merchant_id = '$merchant_id' ");
$stmt -> execute();
if($stmt -> rowCount() > 0)
	show_success($stmt -> fetchAll());
else
	show_error("No suppliers found");
 ?>
