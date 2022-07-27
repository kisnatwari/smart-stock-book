<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(isset($_POST["id"]) && isset($_POST["category"]) && isset($_POST["old_category"])){
		$data = sanitize_var([$_POST['id'], $_POST['category'], $_POST['old_category']]);
		$merchant_id = $_SESSION['logged_in_merchant'];

		//validating
		$q = $pdo -> prepare("SELECT * FROM `categories` WHERE merchant_id = ? and `category` = ?");
		$q -> execute([$_SESSION["logged_in_merchant"], $data[1]]);
		if($q -> rowCount() > 0)
			show_error("$data[1] already exists. Please Try again.");

		if(strlen($data[1]) < 3)
			show_error("Minimum Length of each categories is 3 characters");

		if(strlen($data[1]) > 20)
			show_error("Maximum Length of each categories is 20 characters");

		if(!preg_match("/^[a-zA-Z_\s]+$/",$data[1]))
		    show_error("Invalid characters found in category name");
		

		$stmt = $pdo -> prepare("UPDATE `categories` SET `category` = ? WHERE `merchant_id` = ? AND `category` = ? AND `id`= ? ");
		($stmt -> execute([$data[1], $merchant_id, $data[2], $data[0]])) ? show_success("updated successfully") : show_error("failed to update category");
	}
 ?>