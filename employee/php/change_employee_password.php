<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	$old_pw = !empty($_POST['o_pw']) ? $_POST['o_pw']  : show_error("old password not found");
	$new_pw = !empty($_POST['n_pw']) ? $_POST['n_pw']  : show_error("New password not found");
	$con_pw = !empty($_POST['c_pw']) ? $_POST['c_pw']  : show_error("Confirmation of new password not found");
	if($new_pw != $con_pw)
		show_error("New password and password Confirmation are not same");
	$stmt = $pdo -> prepare("SELECT password FROM users WHERE id = ?");
	$stmt -> execute([$_SESSION["logged_in_id"]]);
	$password = $stmt -> fetch()["password"];
	if(password_verify($old_pw, $password)){
		$stmt = $pdo -> prepare("UPDATE users SET password = ? WHERE id = ?");
		if($stmt -> execute([password_hash($new_pw, PASSWORD_DEFAULT), $_SESSION['logged_in_id']]))
			show_success("Password changed successfully");
		else
			show_error("Failed to change password.. Something went wrong");
	}
	else
		show_error("Old password is not correct");
 ?>