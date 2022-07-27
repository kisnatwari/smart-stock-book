<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	$name = empty(sanitize_var($_POST['name'])) ? show_error("Please provide name...") : sanitize_var($_POST['name']);
	$email = $_SESSION["logged_in_email"];
	//$email = filter_var(sanitize_var($_POST["email"]), FILTER_VALIDATE_EMAIL) ? sanitize_var($_POST["email"]) : show_error("Please provide valid email address");
	$phone = empty(sanitize_var($_POST['phone'])) ? show_error("Please provide phone...") : sanitize_var($_POST['phone']);
	$address = empty(sanitize_var($_POST['address'])) ? show_error("Please provide address...") : sanitize_var($_POST['address']);

	//check mail exiistance
	if($email != $_SESSION['logged_in_email']){
		$stmt = $pdo -> prepare("SELECT * FROM users WHERE email = ?");
		$stmt -> execute([$email]);
		if($stmt -> rowCount() > 0)
			show_error("User with the provided email already exists");
	}
	$stmt = $pdo -> prepare("UPDATE users SET name = '$name' WHERE id = ?; UPDATE employees SET phone = '$phone', address = '$address' WHERE user_id = ?");
	$stmt -> execute([$_SESSION["logged_in_id"], $_SESSION["logged_in_id"]]);
	show_success("Information updated successfully");
 ?>