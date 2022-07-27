<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	print_r($_POST);
	$user_id = $_SESSION['logged_in_id'];
	$merchant_id = $_SESSION['logged_in_merchant'];
	$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
	$stmt = $pdo -> prepare("DELETE FROM customers WHERE `id`=? AND `user_id` = ? AND `merchant_id` = ?");
	($stmt -> execute([$id, $user_id, $merchant_id])) ? show_success("deleted successfully") : show_error($stmt);
 ?>