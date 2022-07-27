<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(!isset($_POST['category']))
		show_error("No category Found");
	$category_id = $_POST['category'];

	$stmt = $pdo -> prepare("
		SELECT brands.category_id, brands.id, brands.brand 
		FROM brands INNER JOIN categories ON brands.category_id = categories.id
		WHERE brands.category_id = ? AND brands.merchant_id = ?
		");
	$stmt -> execute([$category_id, $_SESSION['logged_in_merchant']]);
	show_message("brands",$stmt->fetchAll());
 ?>