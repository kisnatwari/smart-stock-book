<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(isset($_POST["id"]) && isset($_POST["brand"]) && isset($_POST["old_brand"])){
		$data = sanitize_var([$_POST['id'], $_POST['brand'], $_POST['old_brand']]);
		$merchant_id = $_SESSION['logged_in_merchant'];
		$stmt = $pdo -> prepare("UPDATE `brands` SET `brand` = ? WHERE `merchant_id` = ? AND `brand` = ? AND `id`= ? ");
		($stmt -> execute([$data[1], $merchant_id, $data[2], $data[0]])) ? show_success("Brand updated successfully") : show_error("failed to update brand");
	}
 ?>