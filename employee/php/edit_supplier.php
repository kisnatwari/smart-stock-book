<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$user_id = $_SESSION['logged_in_id'];
	$merchant_id = $_SESSION['logged_in_merchant'];
	$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
	$supplier_name = isset($_POST['supplier_name']) ? sanitize_var($_POST['supplier_name']) : null;
	$supplier_email = isset($_POST['supplier_email']) ? sanitize_var($_POST['supplier_email']) : null;
	$supplier_contact = isset($_POST['supplier_contact']) ? sanitize_var($_POST['supplier_contact']) : null;
	$supplier_address = isset($_POST['supplier_address']) ? sanitize_var($_POST['supplier_address']) : null;
	$stmt = $pdo -> prepare("UPDATE suppliers SET `name` = ?, `email` = ?, `contact` = ?, `address` = ? WHERE `user_id` = ? AND `merchant_id` = ? AND `id` = ?");
	$stmt -> execute([$supplier_name, $supplier_email, $supplier_contact, $supplier_address, $user_id, $merchant_id, $id]) ? show_success("updated successfully") : show_error("failed");
 ?>