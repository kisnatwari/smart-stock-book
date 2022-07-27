<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	$user_id = $_SESSION['logged_in_id'];
	$merchant_id = $_SESSION['logged_in_merchant'];
	$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
	$supplier_name = isset($_POST['supplier_name']) ? sanitize_var($_POST['supplier_name']) : null;
	$supplier_email = isset($_POST['supplier_email']) ? sanitize_var($_POST['supplier_email']) : null;
	$supplier_contact = isset($_POST['supplier_contact']) ? sanitize_var($_POST['supplier_contact']) : null;
	$supplier_address = isset($_POST['supplier_address']) ? sanitize_var($_POST['supplier_address']) : null;

		//validating name
	if(!preg_match("/^[a-zA-Z\s]+$/",$supplier_name))
	   show_error("The name you've provided is invalid");

	if($supplier_email != null){
		//checking if email is valid
		if(!filter_var($supplier_email, FILTER_VALIDATE_EMAIL))		show_error("Invalid email");
		$check_email = $pdo -> prepare("SELECT * FROM suppliers WHERE merchant_id = ? AND id <> ? AND email = ?");
		$check_email -> execute([$_SESSION["logged_in_merchant"], $id, $supplier_email]);
		if($check_email -> rowCount() > 0)
			show_error("Another supplier with the provided email address already exists in your supplier's list");
	}

	//validating phone number
	if($supplier_contact != null){
		if(!preg_match("/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/", $supplier_contact))
			show_error("Contact number should be of 10 digits and started with 98 or 97");

		
		$check_contact = $pdo -> prepare("SELECT * FROM suppliers WHERE merchant_id = ? AND id <> ? AND contact = ?");
		$check_contact -> execute([$_SESSION["logged_in_merchant"], $id, $supplier_contact]);
		if($check_contact -> rowCount() > 0)
			show_error("Another supplier with the provided contact number already exists in your supplier's list. Please try changing the contact number");
	}

	if($supplier_address != null){
		//validating address
		if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$supplier_address))
		    show_error("Invalid characters in address");
	}

	if(!$supplier_contact && !$supplier_email)
		show_error("At least email or contact number is required to save the supplier");

	$stmt = $pdo -> prepare("UPDATE suppliers SET `name` = ?, `email` = ?, `contact` = ?, `address` = ? WHERE `user_id` = ? AND `merchant_id` = ? AND `id` = ?");
	$stmt -> execute([$supplier_name, $supplier_email, $supplier_contact, $supplier_address, $user_id, $merchant_id, $id]) ? show_success("updated successfully") : show_error("failed");
 ?>