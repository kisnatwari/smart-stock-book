<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(isset($_POST["id"]) && isset($_POST["category"]) && isset($_POST["old_category"])){
		$data = sanitize_var([$_POST['id'], $_POST['category'], $_POST['old_category']]);
		$merchant_id = $_SESSION['logged_in_merchant'];
		$stmt = $pdo -> prepare("UPDATE `categories` SET `category` = ? WHERE `merchant_id` = ? AND `category` = ? AND `id`= ? ");
		($stmt -> execute([$data[1], $merchant_id, $data[2], $data[0]])) ? show_success("updated successfully") : show_error("failed to update category");
	}
 ?>