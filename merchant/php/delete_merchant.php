<?php 
	header("Content-type:application/json");
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	$user_pw = $_POST['pw'];
	$email = $_SESSION['logged_in_email'];
	$db_pw = $pdo -> prepare("SELECT password, merchant_id FROM users WHERE email = ? AND role = 'merchant'");
	$db_pw -> execute([$email]);
	$db_pw = $db_pw -> fetch();
	$merchant_id = $db_pw["merchant_id"];
	$db_pw = $db_pw["password"];
	if(!password_verify($user_pw, $db_pw))
		show_error("Failed.. Wrong Password");
	if($db -> delete("merchants", $merchant_id)){
		session_destroy();
		show_success("Merchant Account deleted successfully");
	}
	show_error("Failed.. Something went wrong");
 ?>