<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	$user_id = $_SESSION['logged_in_id'];
	$merchant_id = $_SESSION['logged_in_merchant'];
	$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
	$customer_name = isset($_POST['customer_name']) ? sanitize_var($_POST['customer_name']) : null;

	//validating name
	if(!preg_match("/^[a-zA-Z\s]+$/",$customer_name))
	   show_error("The name you've provided is invalid");

	$customer_email = isset($_POST['customer_email']) ? sanitize_var($_POST['customer_email']) : null;
	
	if($customer_email != null){
		//checking if email is valid
		if(!filter_var($customer_email, FILTER_VALIDATE_EMAIL))		show_error("Invalid email");
		$check_email = $pdo -> prepare("SELECT * FROM customers WHERE merchant_id = ? AND id <> ? AND email = ?");
		$check_email -> execute([$_SESSION["logged_in_merchant"], $id, $customer_email]);
		if($check_email -> rowCount() > 0)
			show_error("Another customer with the provided email address already exists in your customer's list");
	}

	$customer_contact = isset($_POST['customer_contact']) ? sanitize_var($_POST['customer_contact']) : null;
	if($customer_contact != null){

		//validating phone number
		if(!preg_match('/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/', $customer_contact))
			show_error("Mobile number should be of 10 digits starting with 98 or 97");


		$check_contact = $pdo -> prepare("SELECT * FROM customers WHERE merchant_id = ? AND id <> ? AND contact = ?");
		$check_contact -> execute([$_SESSION["logged_in_merchant"], $id, $customer_contact]);
		if($check_contact -> rowCount() > 0)
			show_error("Another customer with the provided contact number already exists in your customer's list. Please try changing the contact number");
	}


	$customer_address = isset($_POST['customer_address']) ? sanitize_var($_POST['customer_address']) : null;
	//validating address
	if($customer_address != null)
		if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$customer_address))
		    show_error("Invalid characters in address");

	if(!$customer_contact && !$customer_email)
		show_error("At least email or contact number is required to save the customer");

	$stmt = $pdo -> prepare("UPDATE customers SET `name` = ?, `email` = ?, `contact` = ?, `address` = ? WHERE AND `merchant_id` = ? AND `id` = ?");
	$stmt -> execute([$customer_name, $customer_email, $customer_contact, $customer_address, $merchant_id, $id]) ? show_success("updated successfully") : show_error("failed");
 ?>