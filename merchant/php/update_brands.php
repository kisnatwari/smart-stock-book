<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");

	if(!empty($_POST["id"]) && !empty($_POST["brand"]) && !empty($_POST['category'])){
		$data = sanitize_var([$_POST['id'], $_POST['brand'], $_POST['category']]);
		$merchant_id = $_SESSION['logged_in_merchant'];
		$q = $pdo -> prepare("SELECT * FROM `brands` WHERE merchant_id = ? AND `category_id` = ? AND `brand` = ?");
		$q -> execute([$_SESSION["logged_in_merchant"], $data[2], $data[1]]);
		if($q -> rowCount() > 0)
			show_error("$data[1] already exists in this category. Please Try again.");

		$stmt = $pdo -> prepare("UPDATE `brands` SET `brand` = ? WHERE `merchant_id` = ? AND `category_id` = ? AND `id`= ? ");
		($stmt -> execute([$data[1], $merchant_id, $data[2], $data[0]])) ? show_success("Brand updated successfully") : show_error("failed to update brand");
	}
	else
		show_error("Something is missing. Try again...");
 ?>